# Skill — Intégration IA (V2)

> **Statut** — V2, post-mise en prod V1.
> Cette skill est un **plan d'implémentation**, pas un guide d'utilisation.
> Ne pas commencer le développement avant que la V1 (portfolio statique) soit
> stable en production sur `sonia-habibi.dev`.

## Contexte projet
Feature visée : *générateur de brief automatique* sur `/contact`. Le visiteur
colle l'URL de son site (ou décrit son projet) → un agent Claude analyse le
contexte et propose un brief structuré (objectifs, stack suggérée, devis indicatif),
qui pré-remplit le formulaire ou est envoyé directement par mail.

Sous-objectif : démontrer la compétence "intégration IA" *via* le portfolio
lui-même (le portfolio devient sa propre étude de cas).

---

## 1. Architecture cible

```
┌──────────────────────────┐
│  /contact (PHP view)     │
│  formulaire + bouton     │
│  "Analyser mon site"     │
└──────────┬───────────────┘
           │ POST /api/brief/analyze
           ▼
┌──────────────────────────┐         ┌─────────────────────────────┐
│  PHP orchestrateur       │ ──exec→ │  Python scraper             │
│  App\Services\BriefAi    │         │  scripts/scraper/main.py    │
│  - validation input      │ ←──json─│  - requests / playwright    │
│  - cache (BDD ou file)   │         │  - extract <title>, <h1>,   │
│  - rate limit IP         │         │    <meta>, sample text      │
│  - appel Claude API      │         │  - return JSON              │
│  - format output         │         └─────────────────────────────┘
│  - log usage             │
└──────────┬───────────────┘
           │ HTTPS POST
           ▼
┌──────────────────────────┐
│  Claude API              │
│  api.anthropic.com       │
│  claude-sonnet-4-6       │
└──────────────────────────┘
```

### Pourquoi PHP **et** Python
- PHP = fait déjà partie du stack et gère bien l'orchestration HTTP + sessions.
- Python = ses libs de scraping (BeautifulSoup, Playwright, trafilatura) sont
  largement plus matures que les équivalents PHP.
- Communication par `proc_open()` ou `exec()` avec JSON sur stdin/stdout, pas
  de service séparé en V2 (ajout possible en V3 si volumétrie le justifie).

### Hors scope V2
- Streaming SSE de la réponse Claude (V3, plus complexe).
- File queue (Redis/RabbitMQ) — synchrone pour V2, max 30s de latence acceptés.
- Frontend JS lourd — un simple `fetch()` + spinner suffit.

---

## 2. Structure de fichiers

```
portfolio/
├── app/
│   ├── Controllers/
│   │   └── BriefController.php       ← POST /api/brief/analyze
│   ├── Services/
│   │   ├── BriefAiService.php        ← orchestration (cache, rate limit, Claude)
│   │   ├── ClaudeClient.php          ← wrapper minimal API Claude
│   │   └── ScraperRunner.php         ← exec du script Python
│   └── Models/
│       └── BriefRequest.php          ← persistance + cache (table brief_requests)
├── scripts/
│   └── scraper/
│       ├── main.py                   ← entrypoint, lit URL sur stdin, écrit JSON sur stdout
│       ├── extract.py                ← parsing
│       └── requirements.txt
├── prompts/
│   ├── brief_system.txt              ← system prompt versionné
│   └── brief_user.tpl                ← template user (avec placeholders)
└── storage/
    ├── logs/
    │   └── ai-usage.log              ← log appels Claude (tokens, coût, latence)
    └── cache/
        └── briefs/                   ← cache JSON par hash(URL)
```

---

## 3. Variables d'environnement

À ajouter dans `.env` (et `.env.example` avec valeurs vides) :

```dotenv
# ─── Claude API ───────────────────────────────────────────
ANTHROPIC_API_KEY=
CLAUDE_MODEL=claude-sonnet-4-6
CLAUDE_MAX_TOKENS=2000

# ─── AI feature ───────────────────────────────────────────
AI_ENABLED=false                    # kill switch
AI_CACHE_TTL=86400                  # 24h
AI_RATE_LIMIT_PER_IP_PER_DAY=5
AI_TIMEOUT_SECONDS=30
PYTHON_BIN=/usr/bin/python3
SCRAPER_PATH=scripts/scraper/main.py
```

> **Sécurité** — la clé API n'est lue **que** côté serveur, jamais exposée au
> JS du navigateur. Voir `security.md` § 4 (gestion des secrets).

---

## 4. Schéma BDD — `brief_requests`

```sql
CREATE TABLE brief_requests (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    url_hash        CHAR(64) NOT NULL,                       -- sha256 de l'URL normalisée
    url             VARCHAR(2048) NOT NULL,
    ip_hash         CHAR(64) NOT NULL,                       -- sha256 IP, pas l'IP en clair
    request_payload JSON NOT NULL,                           -- input scraper + form
    response_brief  JSON DEFAULT NULL,                       -- output Claude
    tokens_in       INT DEFAULT 0,
    tokens_out      INT DEFAULT 0,
    cost_usd        DECIMAL(8,4) DEFAULT 0,
    latency_ms      INT DEFAULT 0,
    status          ENUM('pending','done','error','rate_limited') DEFAULT 'pending',
    error_message   TEXT DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_url_hash (url_hash),
    INDEX idx_ip_created (ip_hash, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

L'`ip_hash` permet le rate limiting sans stocker l'IP en clair (RGPD).

---

## 5. Flux côté serveur

### `BriefController::analyze()`
```php
public function analyze(): void
{
    if (($_ENV['AI_ENABLED'] ?? 'false') !== 'true') {
        $this->json(['error' => 'feature_disabled'], 503);
        return;
    }

    $this->verifyCsrf();              // CSRF côté API JSON aussi
    $url = $this->validateUrl($_POST['url'] ?? '');
    if (!$url) {
        $this->json(['error' => 'invalid_url'], 400);
        return;
    }

    try {
        $brief = (new BriefAiService())->generate($url, $this->ipHash());
        $this->json($brief);
    } catch (RateLimitException $e) {
        $this->json(['error' => 'rate_limited'], 429);
    } catch (\Throwable $e) {
        Logger::security('ai_error', ['msg' => $e->getMessage()]);
        $this->json(['error' => 'server_error'], 500);
    }
}
```

### `BriefAiService::generate()` — pipeline
```
1. Vérifier rate limit IP (BDD) — sinon RateLimitException
2. Vérifier cache par url_hash + TTL — sinon scrape
3. Appeler ScraperRunner::run($url) → contenu structuré
4. Construire prompt depuis prompts/brief_*.{txt,tpl}
5. ClaudeClient::send(system, user, max_tokens) → JSON brief
6. Parser, valider la structure, sanitiser
7. Persister en BDD (brief_requests) avec tokens / coût / latence
8. Retourner le brief au controller
```

### Format de retour standardisé
```json
{
  "summary": "Site vitrine d'un restaurant à Lyon, design daté, pas de réservation en ligne.",
  "objectives": ["Refonte design", "Ajout module réservation", "SEO local"],
  "stack_suggested": ["PHP", "MySQL", "API réservation tiers"],
  "estimated_effort_days": 12,
  "indicative_budget_eur": [4800, 7200],
  "next_step": "Discuter par email pour cadrage 30 min."
}
```

> Toujours retourner un schéma stable, validé côté PHP avant rendu — Claude
> peut dévier du format. Si parse fail → tomber sur un message générique +
> log de l'output brut pour itération du prompt.

---

## 6. Scraper Python — minimal

`scripts/scraper/main.py` :
```python
#!/usr/bin/env python3
"""Scraper minimal : prend une URL en argv, écrit JSON sur stdout."""
import json
import sys
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup

ALLOWED_SCHEMES = {"http", "https"}
TIMEOUT = 10
MAX_BYTES = 500_000   # 500 KB

def main(url: str) -> int:
    parsed = urlparse(url)
    if parsed.scheme not in ALLOWED_SCHEMES:
        print(json.dumps({"error": "invalid_scheme"}))
        return 1

    try:
        r = requests.get(url, timeout=TIMEOUT, headers={
            "User-Agent": "SoniaHabibi-PortfolioBot/1.0 (+https://sonia-habibi.dev)"
        })
        r.raise_for_status()
    except requests.RequestException as e:
        print(json.dumps({"error": "fetch_failed", "msg": str(e)}))
        return 2

    html = r.content[:MAX_BYTES]
    soup = BeautifulSoup(html, "html.parser")

    output = {
        "url": url,
        "title": (soup.title.string or "").strip() if soup.title else "",
        "meta_description": _meta(soup, "description"),
        "h1": [h.get_text(strip=True) for h in soup.find_all("h1")][:5],
        "h2": [h.get_text(strip=True) for h in soup.find_all("h2")][:10],
        "text_sample": soup.get_text(" ", strip=True)[:3000],
    }
    print(json.dumps(output, ensure_ascii=False))
    return 0

def _meta(soup, name: str) -> str:
    tag = soup.find("meta", attrs={"name": name})
    return (tag.get("content") or "").strip() if tag else ""

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print(json.dumps({"error": "missing_url"}))
        sys.exit(1)
    sys.exit(main(sys.argv[1]))
```

`requirements.txt` :
```
requests>=2.31
beautifulsoup4>=4.12
```

### Appel depuis PHP — `ScraperRunner::run()`
```php
public function run(string $url): array
{
    $cmd = sprintf(
        '%s %s %s',
        escapeshellcmd($_ENV['PYTHON_BIN'] ?? 'python3'),
        escapeshellarg(ROOT_PATH . '/' . ($_ENV['SCRAPER_PATH'] ?? 'scripts/scraper/main.py')),
        escapeshellarg($url)
    );

    $descriptors = [
        1 => ['pipe', 'w'],   // stdout
        2 => ['pipe', 'w'],   // stderr
    ];
    $proc = proc_open($cmd, $descriptors, $pipes);
    if (!is_resource($proc)) {
        throw new \RuntimeException('Scraper failed to start');
    }

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]); fclose($pipes[2]);
    $code = proc_close($proc);

    if ($code !== 0) {
        Logger::security('scraper_error', ['code' => $code, 'stderr' => $stderr]);
        throw new \RuntimeException('Scraper returned non-zero');
    }

    $data = json_decode($stdout, true);
    if (!is_array($data) || isset($data['error'])) {
        throw new \RuntimeException('Scraper output invalid');
    }
    return $data;
}
```

---

## 7. Prompts — versionnés et externalisés

### `prompts/brief_system.txt`
```
Tu es un consultant tech francophone, concis. Tu reçois la description scrapée
d'un site existant. Ton rôle : produire un brief de mission freelance pour une
développeuse full-stack PHP/Python/IA.

Tu réponds STRICTEMENT en JSON valide, conforme au schéma suivant :
{
  "summary": "string, 1-2 phrases",
  "objectives": ["3-5 objectifs concrets"],
  "stack_suggested": ["technologies pertinentes"],
  "estimated_effort_days": "int, jours·homme",
  "indicative_budget_eur": [min, max],
  "next_step": "phrase d'appel à action"
}
Pas de commentaire hors JSON. Pas de bloc markdown ```json. Juste l'objet.
```

### `prompts/brief_user.tpl`
```
Voici ce qu'on a scrapé du site cible :

URL : {{url}}
Title : {{title}}
Meta description : {{meta_description}}
H1 : {{h1}}
H2 : {{h2}}
Échantillon de texte (tronqué) : {{text_sample}}

Génère le brief.
```

### Bonnes pratiques
- **Versionner les prompts dans git** comme du code — tagger `prompts-v1`,
  `prompts-v2` quand modifs majeures.
- Pas de prompts inline dans le PHP (illisibles + non versionnables).
- A/B tester avec 2–3 variantes avant de figer.

---

## 8. `ClaudeClient.php` — wrapper minimal

```php
<?php
declare(strict_types=1);

namespace App\Services;

class ClaudeClient
{
    private const ENDPOINT = 'https://api.anthropic.com/v1/messages';
    private const VERSION  = '2023-06-01';

    public function send(string $system, string $user, int $maxTokens = 2000): array
    {
        $body = json_encode([
            'model'      => $_ENV['CLAUDE_MODEL'] ?? 'claude-sonnet-4-6',
            'max_tokens' => $maxTokens,
            'system'     => $system,
            'messages'   => [['role' => 'user', 'content' => $user]],
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init(self::ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: '          . $_ENV['ANTHROPIC_API_KEY'],
                'anthropic-version: '  . self::VERSION,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => (int)($_ENV['AI_TIMEOUT_SECONDS'] ?? 30),
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $start = microtime(true);
        $raw   = curl_exec($ch);
        $code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err   = curl_error($ch);
        curl_close($ch);
        $latency = (int) ((microtime(true) - $start) * 1000);

        if ($raw === false) {
            throw new \RuntimeException('Claude API curl error: ' . $err);
        }
        if ($code >= 400) {
            throw new \RuntimeException('Claude API HTTP ' . $code . ': ' . $raw);
        }

        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

        return [
            'text'      => $data['content'][0]['text'] ?? '',
            'usage'     => $data['usage'] ?? [],
            'latency'   => $latency,
        ];
    }
}
```

> Pas de SDK officiel pour PHP ? Wrapper minimal suffit. Le SDK Python existe
> mais on est côté PHP pour l'orchestration.

---

## 9. Sécurité spécifique IA

| Risque                            | Mitigation                                     |
|-----------------------------------|------------------------------------------------|
| Clé API leakée                    | `.env` hors git, jamais dans le JS frontend    |
| URL malicieuse (SSRF interne)     | Whitelist schéma `http(s)`, blocklist `localhost`, `127.0.0.0/8`, `192.168.0.0/16`, `10.0.0.0/8`, `169.254.0.0/16` |
| Prompt injection via contenu scrapé | Tronquer (`max 3000 chars`) + délimiter clairement la zone "scraped content" dans le prompt + system prompt qui rappelle de ne pas exécuter d'instructions du contenu |
| Coût explosif (boucle infinie)    | Rate limit IP, kill switch `AI_ENABLED`, log coût par requête |
| DoS via scraper                   | Timeout `AI_TIMEOUT_SECONDS`, `MAX_BYTES` côté Python |
| Données personnelles dans logs    | Hash IP, troncature texte scraped, log uniquement les tokens/coût/latence |

### Anti-SSRF — vérification d'URL
```php
private function validateUrl(string $url): ?string
{
    $url = trim($url);
    if (!filter_var($url, FILTER_VALIDATE_URL)) return null;
    $parts = parse_url($url);
    if (!in_array($parts['scheme'] ?? '', ['http', 'https'], true)) return null;

    $host = $parts['host'] ?? '';
    $ip   = filter_var($host, FILTER_VALIDATE_IP) ? $host : gethostbyname($host);

    // Bloquer ranges privés et localhost
    $blocked = [
        '127.0.0.0/8', '10.0.0.0/8', '172.16.0.0/12',
        '192.168.0.0/16', '169.254.0.0/16', '::1/128',
    ];
    foreach ($blocked as $range) {
        if ($this->ipInRange($ip, $range)) return null;
    }
    return $url;
}
```

---

## 10. Coût et observabilité

### Tracking par requête
Chaque entrée `brief_requests` doit logger :
- `tokens_in`, `tokens_out` (depuis `usage` de la réponse Claude)
- `cost_usd` (calculé d'après tarifs Sonnet à jour)
- `latency_ms`

### Tarifs Claude Sonnet 4.6 (vérifier sur la doc avant la mise en prod)
À mettre dans une constante :
```php
private const COST_PER_MTOKEN_INPUT  = 3.00;   // USD / 1M tokens
private const COST_PER_MTOKEN_OUTPUT = 15.00;  // USD / 1M tokens
```
Les valeurs exactes peuvent changer — vérifier sur
`https://docs.claude.com/en/docs/about-claude/pricing` avant déploiement.

### Dashboard admin
Page `/admin/ai/usage` listant briefs des 30 derniers jours, total tokens,
coût total, top URLs. Réutilise le layout `admin/`.

### Alerte budget
Si total quotidien > seuil (ex : 5 USD) → envoi d'un mail à `CONTACT_TO` et
désactivation automatique (`AI_ENABLED=false` via flag en BDD).

---

## 11. UX côté front

### Page `/contact` — bloc IA
```html
<details class="ai-helper">
  <summary><?= $t('ai.helper.summary') ?></summary>
  <p><?= $t('ai.helper.desc') ?></p>
  <form id="briefForm" action="<?= $base ?>/api/brief/analyze" method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <label for="targetUrl"><?= $t('ai.helper.url') ?></label>
    <input type="url" id="targetUrl" name="url" required placeholder="https://…">
    <button type="submit" class="btn btn--outline btn--sm"><?= $t('ai.helper.go') ?></button>
  </form>
  <output id="briefResult" class="ai-helper__result" hidden></output>
</details>
```

### JS minimal — `main.js`
```js
const form = document.getElementById('briefForm');
const out  = document.getElementById('briefResult');
form?.addEventListener('submit', async (e) => {
  e.preventDefault();
  out.hidden = false;
  out.textContent = '⏳ Analyse en cours…';
  try {
    const res = await fetch(form.action, { method: 'POST', body: new FormData(form) });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const brief = await res.json();
    out.innerHTML = renderBrief(brief);   // helper qui formate
    document.getElementById('message').value = briefToText(brief);  // pré-remplit le form
  } catch (err) {
    out.textContent = '❌ Une erreur est survenue. Réessayez.';
    console.error(err);
  }
});
```

### Accessibilité
- `<output>` annoncé par lecteur d'écran (`role="status"` implicite).
- Bouton submit `aria-busy="true"` pendant l'attente.
- Ne pas remplacer le formulaire — le **compléter**, l'utilisateur garde la main.

---

## 12. Plan de mise en œuvre — par étapes

### Étape 1 — fondations (1–2 j)
- [ ] Migration BDD `brief_requests`
- [ ] `.env` + `.env.example` mis à jour
- [ ] `prompts/` versionné en git
- [ ] Squelette `BriefController`, `BriefAiService`, `ClaudeClient`, `ScraperRunner`

### Étape 2 — scraper (1 j)
- [ ] `scripts/scraper/main.py` + `requirements.txt`
- [ ] Test isolé : `python3 scripts/scraper/main.py https://example.com`
- [ ] Wrapper PHP `ScraperRunner` testé via tinker / script CLI

### Étape 3 — Claude (1 j)
- [ ] `ClaudeClient::send()` testé sur appel simple
- [ ] Prompts itérés sur 3–5 sites variés
- [ ] Validation du JSON de retour côté PHP (schema check)

### Étape 4 — orchestration (1 j)
- [ ] Cache fichier ou BDD avec TTL
- [ ] Rate limit par IP
- [ ] Logging tokens/coût/latence
- [ ] Anti-SSRF testé

### Étape 5 — UX (0.5 j)
- [ ] Form `/contact` avec bloc IA
- [ ] JS d'envoi + rendu
- [ ] Traductions FR/EN
- [ ] Skip si JS désactivé (form contact reste utilisable)

### Étape 6 — admin & monitoring (0.5 j)
- [ ] Page `/admin/ai/usage`
- [ ] Alerte budget
- [ ] Test kill switch `AI_ENABLED=false`

### Étape 7 — prod (0.5 j)
- [ ] Vérif `python3` dispo sur o2switch (sinon embarquer venv)
- [ ] Vérif `proc_open` non désactivé en `disable_functions`
- [ ] Variables d'env Claude posées en prod
- [ ] Smoke test sur prod avec URL réelle

---

## 13. Critères d'acceptation V2

- [ ] La feature peut être désactivée à chaud via `.env` sans casser le portfolio
- [ ] Aucune clé API exposée côté navigateur (vérif via DevTools)
- [ ] Rate limit fonctionne (6e tentative dans la journée → 429)
- [ ] Cache fonctionne (2 requêtes même URL → 1 seul appel Claude)
- [ ] Coût mensuel suivi en BDD, alerte > seuil
- [ ] Anti-SSRF empêche `http://localhost`, `http://192.168.x.x`
- [ ] Toutes les erreurs sont loggées dans `storage/logs/ai-usage.log`
- [ ] Le formulaire contact reste fonctionnel sans IA (kill switch ON)

---

## 14. Sources & ressources

- Doc Claude API : https://docs.claude.com
- Tarifs : https://docs.claude.com/en/docs/about-claude/pricing
- Best practices prompts : https://docs.claude.com/en/docs/build-with-claude/prompt-engineering/overview
- OWASP LLM Top 10 : https://owasp.org/www-project-top-10-for-large-language-model-applications/
- Anti-SSRF guide : https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html
