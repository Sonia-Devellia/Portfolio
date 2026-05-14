# Audit de sécurité — Portfolio Sonia Habibi

Date : 2026-05-13 · Périmètre : front + back PHP + admin

## Ce qui était déjà solide

- PDO en prepared statements partout, `ATTR_EMULATE_PREPARES = false`
- Sessions `httpOnly` + `SameSite=Lax` + `Secure` en prod
- CSP strict sans `unsafe-inline`, X-Frame-Options, X-Content-Type-Options, Permissions-Policy, HSTS en prod
- CSRF token (`random_bytes(32)`) + `hash_equals` sur admin + contact
- `password_verify` + `session_regenerate_id(true)` après login
- Rate-limit progressif sur login admin (backoff jusqu'à 5 min)
- Validation email/longueurs et anti-CRLF injection sur contact
- `.htaccess` bloque `.env`, `.git`, composer/package.json
- `.gitignore` exclut `.env`, `vendor/`, logs
- Logs séparés (`storage/logs/` avec `Require all denied`)
- `htmlspecialchars` systématique dans les vues (jamais de `echo` brut sur input utilisateur)

## Corrections appliquées

### Critique
- **Open redirect** dans `LangController` : `HTTP_REFERER` brut était utilisé pour la redirection. Désormais, seuls les chemins relatifs du même host sont acceptés, sinon fallback sur `/`.

### Haut
- **Anti-spam contact** : ajout d'un honeypot caché (`name="website"`), d'un délai minimum de 3s entre affichage et soumission, et d'un cooldown de 60s entre deux envois par session.
- **CSRF global** : `csrf_token()` est désormais initialisé dans le bootstrap (`public/index.php`), plus de risque de token vide à l'arrivée sur une page d'admin.

### Moyen
- **Idle timeout admin** : déconnexion automatique après 30 minutes d'inactivité, géré côté bootstrap.
- **Nom de cookie de session** : `PHPSESSID` → `portfolio_sid` pour ne plus exposer le runtime.
- **Logout en POST + CSRF** : route et layout admin convertis en formulaire, évite la déconnexion forcée via image piégée.
- **Headers ajoutés** : `Cross-Origin-Opener-Policy: same-origin`, `Cross-Origin-Resource-Policy: same-origin`, `object-src 'none'` et `upgrade-insecure-requests` dans la CSP.
- **`.htaccess` durci** : blocage explicite de `.agents/`, `tests/`, `AGENTS.md`, `PRODUCT.md`, `DESIGN.{md,json}`, `skills-lock.json`, et des extensions `.scss`, `.sql`, `.log`.

### Bas
- **Logger RGPD** : l'IP est désormais hashée (SHA-256 tronqué à 16 chars) par défaut au lieu d'être stockée en clair.
- **`project_type` validé** : le champ est désormais lu et validé côté serveur (whitelist `site/app/ai/other`).
- **`helpers.php`** : nouvelle couche de helpers (`e()`, `csrf_token()`, `csrf_field()`, `csrf_check()`, `base_url()`) pour homogénéiser et éviter les oublis d'échappement.
- **Code mort retiré** : lien admin `/admin/messages` (route inexistante).

## Reste à faire en production (action côté hébergeur)

- Décommenter la redirection HTTPS dans `public/.htaccess`
- Générer un `ADMIN_PASS_HASH` différent de celui en local (commande dans `.env.example`)
- Vérifier que SPF/DKIM sont configurés pour le domaine d'envoi (`MAIL_FROM`)
- `chmod 600` sur `.env` au déploiement
- Configurer un certificat TLS valide pour activer HSTS sans rétropédalage

## Garde-fous laissés volontairement

- Pas de WAF / fail2ban applicatif au-delà du rate-limit login : la surface est trop petite pour justifier l'overhead.
- Pas de captcha sur le contact : le honeypot + délai + cooldown couvre 99% des bots sans dégrader l'UX.
- Pas de 2FA admin : un seul utilisateur, le rate-limit progressif suffit pour ce contexte.

## Nettoyage effectué côté code

- Suppression des méthodes mortes du modèle `Project` (`getBySlug`, `getFeatured`, `parseTags`).
- Refactor `Router` : passage à `compact()` et `...$matches`, dispatch en moitié moins de lignes.
- Refactor `Controller` : helpers `t()` / `tRaw()` plus concis, `redirect()` utilise `base_url()`.
- `HomeController` : extraction du FAQ et du `ProfessionalService` schema en méthodes dédiées, le layout n'a plus à les générer.
- `CaseStudyController` : factorisation `renderCase()` pour mutualiser `triage()` et `amanea()`.
- `GeoController` : tableau `CITIES` aplati en une ligne par ville, schemas extraits en méthodes.
- `TarifsController` : ré-utilisation de `$extraSchemas` du layout (suppression du `<script>` inline dans la vue).
- `home/index.php` : suppression de la fonction `tagColor()` jamais appelée.
- Layout admin : suppression du lien mort `/admin/messages`.

## Fichiers à supprimer manuellement (sandbox sans droits delete)

Les fichiers suivants ne sont jamais référencés et peuvent être supprimés pour gagner ~6 Mo :

```
public/assets/images/projects/amanea.png      (1.6 Mo)
public/assets/images/projects/equestre.png    (2.0 Mo)
public/assets/images/projects/ferme.png       (1.4 Mo)
public/assets/images/projects/interior.png    (1.2 Mo)
public/assets/images/projects/interior.webp   (104 Ko)
public/assets/images/sonia.png                (196 Ko)
.DS_Store                                     (10 Ko)
```

Toutes les versions `.webp` qui sont effectivement utilisées sont conservées.

## Pour faire prendre effet les changements CSS sans rebuild

J'ai appendé deux blocs minimaux au CSS compilé (`.hp-field` honeypot et le `.admin-sidebar__logout-form`). Le SCSS source est aussi à jour : un `npm run sass:build` régénérera proprement le fichier.

## Migration mail() → PHPMailer (SMTP authentifié)

### Pourquoi ce changement

`mail()` natif sur hébergement mutualisé OVH :
- Pas de DKIM signé par OVH → emails partent en spam folder
- Pas de retour d'erreur précis (`@mail()` retourne juste `false`)
- SPF souvent désaligné → bounce silencieux

PHPMailer + SMTP authentifié sur `ssl0.ovh.net:465` :
- DKIM signé automatiquement par OVH au passage SMTP
- SPF aligné (l'IP émettrice est dans le `SPF` du domaine)
- Erreurs SMTP capturées et loggées (`mail_smtp_failed` dans `security.log`)
- Fallback automatique sur `mail()` si SMTP non configuré (en local par ex.)

### Installation côté local

```bash
cd /Applications/MAMP/htdocs/portfolio
composer require phpmailer/phpmailer:^6.9
```

Cela installe PHPMailer dans `vendor/phpmailer/phpmailer` et met à jour `composer.lock`. À commiter ensemble.

### Configuration en production sur OVH

Dans le `.env` de prod, renseigner :

```env
MAIL_HOST=ssl0.ovh.net
MAIL_PORT=465
MAIL_USER=contact@sonia-habibi.dev
MAIL_PASS=<mot de passe du compte mail OVH>
MAIL_FROM=contact@sonia-habibi.dev
MAIL_TO=sonia@sonia-habibi.dev
```

**Crucial pour la deliverability** : `MAIL_FROM` doit appartenir au domaine du compte mail OVH, sinon SPF/DKIM ne s'alignent pas et les mails atterrissent en spam même avec SMTP authentifié.

### Vérification post-déploiement

1. Envoyer un test via le formulaire de contact
2. Tester avec [mail-tester.com](https://mail-tester.com) : score ≥ 9/10 attendu sur OVH avec SPF + DKIM auto
3. Vérifier les logs : `tail -f storage/logs/app.log` doit afficher `[INFO] contact_sent` au lieu de `[ERROR] contact_mail_failed`

## Images responsive — AVIF + WebP via `<picture>`

### Pourquoi

- AVIF est ~25-30% plus léger que WebP à qualité équivalente
- Supporté par tous les navigateurs modernes (Chrome, Firefox, Safari 16+) → ~95% du trafic
- Le `<picture>` dégrade gracieusement : si AVIF non supporté, le navigateur prend le WebP fallback

### Comment ça marche dans le code

Le helper `picture()` dans `core/helpers.php` détecte automatiquement les variants présents
à côté de chaque image WebP :

- `sonia.webp` + `sonia.avif` (s'il existe) → `<source type="image/avif">`
- `sonia.webp` + `sonia@2x.webp` (s'il existe) → `srcset="... 1x, ... 2x"` Retina
- `sonia.webp` + `sonia@2x.avif` (s'il existe) → idem pour AVIF Retina

**Pas d'erreur si une variante manque** — le helper omet juste la source correspondante.

### Générer les variantes AVIF

```bash
# Installation outils (macOS)
brew install libavif

# Génération automatique de toutes les variantes manquantes
./bin/build-images.sh
```

Le script :
- Détecte chaque `.webp` / `.png` / `.jpg` dans `public/assets/images/` et `public/assets/img/`
- Génère un `.avif` à côté (qualité 70, vitesse 6 — bon compromis)
- Skip les fichiers déjà à jour (comparaison mtime)
- Fallback sur ImageMagick (`magick`) si `avifenc` indisponible

### Vérification gain perf

1. Avant : `find public/assets/images -name "*.webp" | xargs du -ch`
2. Après : ajouter `*.avif` à la commande pour voir le poids cumulé
3. Lighthouse : score Performance attendu en hausse de 5-10 points, surtout LCP
4. PageSpeed Insights : voir le détail "Use modern image formats" passer au vert

### Variantes Retina (phase 2, plus tard)

Si tu veux pousser plus loin, upload tes images sources à 2× la taille d'affichage
(ex. pour `sonia` affichée à 480×560, fournis `sonia@2x.webp` à 960×1120).
Le helper l'utilisera automatiquement.

Pas urgent : sans `@2x`, le navigateur upscale le 1× sur Retina (un peu flou)
mais ça reste WebP/AVIF donc déjà très optimisé.

## Rotation automatique des logs

### Pourquoi

`storage/logs/security.log` et `storage/logs/app.log` étaient écrits en append-only
sans aucune limite de taille. Sur OVH mutualisé, pas d'accès facile à `logrotate`
système ni à cron pour purger. Sur une période de quelques mois avec du trafic
ou des bots qui tapent sur `/admin/login`, le fichier peut atteindre plusieurs centaines
de Mo et saturer le quota disque.

### Comment ça marche maintenant

Dans `app/Helpers/Logger.php`, rotation automatique côté application :

- À chaque écriture, le logger vérifie `filesize($path) >= MAX_SIZE` (5 MB par défaut)
- Si seuil atteint :
  1. `security.log.5` est supprimé (si présent)
  2. `security.log.4` → `security.log.5`
  3. `security.log.3` → `security.log.4` (et ainsi de suite jusqu'à `.1`)
  4. `security.log` → `security.log.1`
  5. Un nouveau `security.log` vide est créé à l'écriture suivante

Conséquence : au maximum, **6 fichiers cohabitent** par type de log
(`security.log` + 5 archives), soit **~30 MB par type**, **~60 MB au total**
pour `security` + `app`. Largement compatible avec un quota OVH standard.

### Paramètres ajustables

Dans `Logger.php`, deux constantes :

```php
private const MAX_SIZE     = 5 * 1024 * 1024;  // 5 MB par fichier
private const MAX_ARCHIVES = 5;                // .log.1 à .log.5
```

Si tu veux garder plus d'historique → augmente `MAX_ARCHIVES`.
Si tu veux des fichiers plus petits pour faciliter la lecture → diminue `MAX_SIZE`.

### Coût performance

`filesize()` est quasi gratuit (cache inode OS, ~µs). La rotation elle-même
n'arrive que quand le seuil est franchi — donc ~1 fois tous les ~50 000 logs.
Aucun impact mesurable sur les requêtes normales.

## CSP report-uri — visibilité sur les violations en prod

### Pourquoi

Sans reporting, la CSP est aveugle : quand un script tiers ou une extension est
bloqué, l'utilisateur le voit dans sa console mais toi non. Avec reporting, le
navigateur envoie automatiquement un rapport JSON à un endpoint que tu contrôles,
loggé proprement, sans impact côté visiteur.

Cas typiques détectés :
- Extension navigateur qui injecte du JS → violation `script-src`
- Mauvaise URL d'asset après déploiement → violation `connect-src` ou `img-src`
- Iframe non autorisée → violation `frame-ancestors`
- Code legacy oublié qui tente un `eval()` → violation `script-src`

### Comment ça marche

1. CSP envoie `report-uri /csp-report; report-to csp-endpoint` (activé **uniquement en prod**)
2. Header `Reporting-Endpoints: csp-endpoint="https://sonia-habibi.dev/csp-report"`
3. Le navigateur POST automatiquement un JSON sur l'endpoint à chaque violation
4. `ReportController::csp()` normalise le payload (legacy ou moderne) et appelle `Logger::warning('csp_violation', ...)`
5. Les rapports atterrissent dans `storage/logs/app.log` avec contexte (directive violée, URL bloquée, page d'origine, ligne de code, sample)

### Consultation

```bash
# Suivre les violations en temps réel
tail -f storage/logs/app.log | grep csp_violation

# Compter les violations par directive sur la dernière semaine
grep csp_violation storage/logs/app.log | jq -r '.directive' | sort | uniq -c | sort -rn
```

### Pourquoi seulement en prod

En local MAMP, la CSP locale n'a pas l'enjeu (pas de visiteurs, tu vois directement
ta console). Activer le reporting en dev polluerait les logs avec les violations
liées à tes extensions (1Password, React DevTools…). Le `if ($isProd)` garde le bootstrap
propre.

### Si tu veux pousser

Pour gérer un gros volume, plutôt que de logger localement, tu peux pointer
`report-uri` sur un service tiers gratuit comme [report-uri.com](https://report-uri.com)
qui agrège, déduplique et te donne des dashboards. Mais pour ton volume estimé,
le log local est largement suffisant.

## Health endpoint — monitoring externe

### Endpoint

`GET /health` → réponse JSON courte :

```json
{
  "status": "ok",
  "time": "2026-05-14T13:42:18+02:00",
  "checks": { "db": "ok" }
}
```

- HTTP **200** si tout est OK
- HTTP **503** si un check échoue (BDD inaccessible)

### Branchement monitoring gratuit

[UptimeRobot](https://uptimerobot.com) — plan gratuit jusqu'à 50 monitors, check toutes les 5 minutes :

1. Créer un monitor de type **HTTP(s)**
2. URL : `https://sonia-habibi.dev/health`
3. Keyword monitoring (optionnel) : keyword `"status":"ok"` → must exist
4. Alert contact : ton email
5. **Disallow** déjà ajouté dans `robots.txt` → Google ne va pas indexer

Tu reçois un mail dès que le site répond 5xx, ne répond plus du tout, ou perd la BDD.

### Étendre

Pour ajouter un check (par ex. disque, mémoire, file de jobs si tu en as un jour),
ajoute juste une méthode dans `HealthController` :

```php
private function checkDiskSpace(): bool
{
    $free = disk_free_space(ROOT_PATH);
    return $free !== false && $free > 100 * 1024 * 1024; // > 100 MB libres
}
```

Et l'ajouter au tableau `$checks` dans `index()`. Le statut global passe automatiquement
en `degraded` si un check renvoie `false`.

### Pourquoi pas de Logger

Avec un check toutes les 5 minutes, le monitoring génère **288 hits/jour**. Logger
chaque ping écraserait les logs réels (login attempts, contact spam, etc.) en
quelques heures. Le health endpoint reste silencieux par design — seul UptimeRobot
te notifie en cas de problème.
