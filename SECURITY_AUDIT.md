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
