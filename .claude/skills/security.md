# Skill — Sécurité (durcissement prod)

## Contexte projet
Stack PHP 8.1 MVC, MySQL via PDO, sessions PHP natives, dotenv (`vlucas/phpdotenv`).
Hébergement cible : o2switch (Apache + .htaccess), domaine `sonia-habibi.dev`.

> **Périmètre de cette skill** — durcissement prod : headers HTTP, .htaccess,
> secrets, hardening admin, logs, désactivation des erreurs.
> Les bases (CSRF, htmlspecialchars, prepared statements, sessions) sont déjà
> dans `code-quality.md` § 2 — ne pas dupliquer.

---

## 1. OWASP Top 10 — état du projet

| Risque                          | Statut | Localisation                                    |
|---------------------------------|:------:|-------------------------------------------------|
| A01 Broken Access Control       |   ✓    | `AdminController::guard()`                      |
| A02 Cryptographic Failures      |   ◑    | HTTPS à activer côté .htaccess + HSTS           |
| A03 Injection (SQL/XSS)         |   ✓    | PDO préparé + `htmlspecialchars` partout        |
| A04 Insecure Design             |   ◑    | Pas de rate limiting sur `/admin/login`         |
| A05 Security Misconfiguration   |   ◑    | Erreurs PHP non masquées en prod                |
| A06 Vulnerable Components       |   ◑    | `composer audit` à intégrer                     |
| A07 Auth & Session Failures     |   ✓    | `session_regenerate_id(true)` + cookie httponly |
| A08 Software/Data Integrity     |   ◑    | Pas de SRI sur les fontes externes              |
| A09 Logging & Monitoring        |   ✗    | Aucun log applicatif                            |
| A10 SSRF                        |   N/A  | Pas d'appel sortant en V1                       |

✓ = en place — ◑ = partiel — ✗ = à faire — N/A = non applicable

---

## 2. Headers HTTP — `public/index.php`

### État actuel
```php
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
```

### Cible prod — bloc complet
```php
// Anti-clickjacking
header('X-Frame-Options: SAMEORIGIN');

// MIME sniffing désactivé
header('X-Content-Type-Options: nosniff');

// Referrer minimal
header('Referrer-Policy: strict-origin-when-cross-origin');

// HSTS — uniquement en prod, après vérif HTTPS OK
if (($_ENV['APP_ENV'] ?? 'local') === 'production') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// Permissions — couper tout ce qu'on n'utilise pas
header("Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()");

// CSP — strict mais compatible avec Google Fonts + JSON-LD inline
header("Content-Security-Policy: "
    . "default-src 'self'; "
    . "script-src 'self'; "
    . "style-src 'self' https://fonts.googleapis.com; "
    . "font-src 'self' https://fonts.gstatic.com; "
    . "img-src 'self' data: https:; "
    . "connect-src 'self'; "
    . "frame-ancestors 'self'; "
    . "form-action 'self'; "
    . "base-uri 'self'"
);
```

> **Note CSP** — le JSON-LD est `<script type="application/ld+json">`, pas
> exécutable, donc pas concerné par `script-src`. Si un jour un script inline
> est nécessaire, ajouter `'nonce-XXX'` et générer le nonce par requête —
> jamais `'unsafe-inline'`.

---

## 3. `.htaccess` prod — `public/.htaccess`

### État actuel
HTTPS commenté, pas de protection des fichiers sensibles, pas de désactivation
du listing, pas de cache statique.

### Cible prod
```apache
RewriteEngine On

# ─── HTTPS forcé ─────────────────────────────────────────────
RewriteCond %{HTTPS} off
RewriteCond %{HTTP:X-Forwarded-Proto} !https
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# ─── www → apex (ou inverse selon préférence) ────────────────
RewriteCond %{HTTP_HOST} ^www\.sonia-habibi\.dev$ [NC]
RewriteRule ^(.*)$ https://sonia-habibi.dev/$1 [L,R=301]

# ─── Routing front-controller ────────────────────────────────
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# ─── Désactiver le listing de répertoire ─────────────────────
Options -Indexes -MultiViews

# ─── Bloquer l'accès aux fichiers sensibles ──────────────────
<FilesMatch "(\.env|\.env\..*|composer\.(json|lock)|package(-lock)?\.json|\.git.*|\.htaccess|\.htpasswd|README\.md|CLAUDE\.md)$">
    Require all denied
</FilesMatch>

# ─── Bloquer les dossiers internes (au cas où mauvaise racine) ─
RewriteRule ^(app|core|config|lang|vendor|node_modules|\.claude|\.superpowers)/ - [F,L]

# ─── Cache statique long ─────────────────────────────────────
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/webp                "access plus 1 year"
    ExpiresByType image/avif                "access plus 1 year"
    ExpiresByType image/jpeg                "access plus 1 year"
    ExpiresByType image/png                 "access plus 1 year"
    ExpiresByType image/svg+xml             "access plus 1 year"
    ExpiresByType text/css                  "access plus 1 month"
    ExpiresByType application/javascript    "access plus 1 month"
    ExpiresByType font/woff2                "access plus 1 year"
</IfModule>

# ─── Compression ─────────────────────────────────────────────
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css application/javascript image/svg+xml application/json
</IfModule>

# ─── Désactiver la signature serveur ─────────────────────────
ServerSignature Off
```

### `.htaccess` à la racine du projet
À créer pour bloquer l'accès direct au reste si DocumentRoot mal configuré :
```apache
# /Applications/MAMP/htdocs/portfolio/.htaccess
Require all denied
```
(Le DocumentRoot prod doit pointer sur `/public/`, mais ceinture + bretelles.)

---

## 4. Secrets et `.env`

### Règles strictes
- `.env` **jamais** commité — `.gitignore` doit contenir `.env` et `.env.*` (sauf `.env.example`).
- Hash admin : `password_hash($pass, PASSWORD_DEFAULT)` — ne **jamais** stocker en clair.
- Rotation : générer un nouveau `ADMIN_PASS_HASH` avant prod, différent du hash dev.

### `.env.example` — template à commiter
```dotenv
APP_ENV=local
APP_URL=http://localhost:8888/portfolio/public
DEFAULT_LANG=fr

DB_HOST=127.0.0.1
DB_PORT=8889
DB_NAME=portfolio
DB_USER=root
DB_PASS=

ADMIN_USER=
ADMIN_PASS_HASH=

CONTACT_TO=
SMTP_HOST=
SMTP_PORT=
SMTP_USER=
SMTP_PASS=
```

### Génération du hash admin
```bash
php -r "echo password_hash('mon_mot_de_passe_fort', PASSWORD_DEFAULT) . PHP_EOL;"
```

### Vérification git avant push
```bash
# .env ne doit pas apparaître
git ls-files | grep -E '^\.env$'  # → vide attendu
git log --all --full-history -- .env  # → vide attendu
```

Si `.env` a été commité par erreur :
```bash
git filter-repo --path .env --invert-paths   # réécrit l'historique
# Puis : changer TOUS les secrets (DB pass, admin hash, SMTP)
```

---

## 5. Erreurs PHP — masquage en prod

### Cible — début de `public/index.php`
À ajouter **avant** tout autre code :
```php
$isProd = ($_ENV['APP_ENV'] ?? 'local') === 'production';

if ($isProd) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', ROOT_PATH . '/storage/logs/php-errors.log');
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
```

> Le dossier `storage/logs/` doit exister et être en écriture par PHP, mais
> **hors** de `public/` pour ne pas être accessible en HTTP.

### `Core\Database` — masquage du message PDO en prod
Déjà identifié dans `code-quality.md` § 2. Rappel :
```php
$message = ($_ENV['APP_ENV'] ?? 'prod') === 'local'
    ? 'Connexion BDD échouée : ' . $e->getMessage()
    : 'Erreur de connexion. Contactez l\'administrateur.';
throw new \RuntimeException($message);
```

---

## 6. Hardening de l'admin

### Rate limiting basique sur `/admin/login`
Stocker les tentatives par IP en session côté login (le visiteur n'a pas
encore de session admin) :
```php
// Dans AdminController::loginPost(), tout en haut
$attempts = $_SESSION['login_attempts'] ?? ['count' => 0, 'until' => 0];

if ($attempts['count'] >= 5 && time() < $attempts['until']) {
    http_response_code(429);
    $_SESSION['admin_error'] = 'rate_limit';
    $this->redirect('/admin/login');
    return;
}

// ... vérif credentials ...

if ($validUser && $validPass) {
    unset($_SESSION['login_attempts']);
    session_regenerate_id(true);
    $_SESSION['admin'] = true;
    $this->redirect('/admin/projets');
} else {
    $_SESSION['login_attempts'] = [
        'count' => $attempts['count'] + 1,
        'until' => time() + 60 * min(5, $attempts['count'] + 1), // backoff 1→5 min
    ];
    $_SESSION['admin_error'] = 'credentials';
    $this->redirect('/admin/login');
}
```

> Pour un vrai rate limiting cross-IP, passer en BDD ou Redis. Pour un portfolio
> à faible trafic, la session suffit.

### Robots.txt — bloquer l'admin
Voir `seo.md` § 5 — `Disallow: /admin/` déjà prévu ✓.

### Pas d'indication de l'erreur exacte
Login fail → message générique côté vue (`identifiants invalides`), jamais
"utilisateur inconnu" vs "mot de passe incorrect" — sinon énumération possible.

### Cookie session admin renforcé en prod
`public/index.php` pose déjà `httponly + samesite=Lax`. En prod, forcer `secure` :
```php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $isProd,      // ← passer à true en prod
    'httponly' => true,
    'samesite' => 'Lax',
]);
```

---

## 7. Validation et sanitisation des inputs

### Règles de base
- Tout `$_POST`/`$_GET`/`$_REQUEST` est **toujours** considéré hostile.
- `trim()` systématique avant validation.
- Whitelist > blacklist : `in_array()` strict pour des choix limités.
- Pour les URLs : `filter_var($url, FILTER_VALIDATE_URL)` + vérif schéma `http(s)://` uniquement.

### Patterns du projet

```php
// ✓ Slug — alphanum + tirets uniquement
$slug = preg_replace('/[^a-z0-9-]/', '', strtolower(trim($_POST['slug'] ?? '')));
if ($slug === '') { /* erreur */ }

// ✓ ID — toujours int
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) { /* erreur */ }

// ✓ URL externe (github_url, demo_url) — schéma whitelist
$url = trim($_POST['github_url'] ?? '');
if ($url !== '') {
    $valid = filter_var($url, FILTER_VALIDATE_URL)
          && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true);
    if (!$valid) { /* erreur */ }
}

// ✓ Email contact
$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { /* erreur */ }

// ✓ Texte libre (message, description) — limiter la taille
$message = mb_substr(trim($_POST['message'] ?? ''), 0, 5000);
```

### `AdminController::sanitizeProjectPost()` — à renforcer
La méthode actuelle fait `trim()` mais ne valide ni les URLs ni le slug. À
compléter dans la même fonction ou une `validateProjectPost()` séparée qui
remonte les erreurs au form.

---

## 8. Logging — minimum viable

Créer `app/Helpers/Logger.php` :
```php
<?php
declare(strict_types=1);

namespace App\Helpers;

class Logger
{
    public static function security(string $event, array $context = []): void
    {
        $line = sprintf(
            "[%s] [SECURITY] %s | ip=%s | ua=%s | ctx=%s\n",
            date('c'),
            $event,
            $_SERVER['REMOTE_ADDR'] ?? '?',
            substr($_SERVER['HTTP_USER_AGENT'] ?? '?', 0, 200),
            json_encode($context, JSON_UNESCAPED_UNICODE)
        );
        @file_put_contents(
            ROOT_PATH . '/storage/logs/security.log',
            $line,
            FILE_APPEND | LOCK_EX
        );
    }
}
```

Événements à logger : login échoué, login réussi, CSRF rejeté, rate limit
déclenché, accès refusé sur route admin.

---

## 9. Dépendances — `composer audit`

À intégrer dans le workflow avant chaque déploiement :
```bash
composer audit
composer update --dry-run  # voir ce qui pourrait être upgradé
```

Si une CVE est signalée → upgrade ou pin de version dans `composer.json`.

---

## 10. Checklist sécurité avant la mise en prod

### Code
- [ ] `APP_ENV=production` dans `.env` prod
- [ ] `display_errors=0` actif (vérifier via `phpinfo()` côté prod)
- [ ] `Core\Database` masque le message PDO en prod
- [ ] `secure=true` sur le cookie session en prod
- [ ] HSTS activé conditionnellement sur `APP_ENV=production`
- [ ] CSP active et testée (vérifier la console navigateur, pas de violation)

### Fichiers et secrets
- [ ] `.env` absent de git (`git ls-files | grep .env` vide)
- [ ] `ADMIN_PASS_HASH` régénéré pour la prod (différent du dev)
- [ ] `storage/logs/` existe, en écriture, hors de `public/`

### Apache / .htaccess
- [ ] HTTPS forcé (redirect 301)
- [ ] `Options -Indexes` actif
- [ ] `<FilesMatch>` bloque .env, composer.*, .git*, README.md, CLAUDE.md
- [ ] `RewriteRule ^(app|core|config|lang|vendor)/` retourne 403

### Formulaires
- [ ] CSRF token sur `/contact`, `/admin/login`, create/update/delete projet
- [ ] Vérification `hash_equals()` côté controller pour chaque POST
- [ ] Message d'erreur login générique (pas d'énumération)

### Admin
- [ ] Rate limiting actif sur `/admin/login` (5 essais → 1 min, puis backoff)
- [ ] `robots.txt` `Disallow: /admin/`
- [ ] Logout fait `session_regenerate_id(true)` + `unset($_SESSION['admin'])`

### Audit final
- [ ] `composer audit` ne signale aucune CVE
- [ ] Headers vérifiés via [securityheaders.com](https://securityheaders.com) → cible A+
- [ ] SSL vérifié via [SSL Labs](https://www.ssllabs.com/ssltest/) → cible A
