# Skill — Lighthouse & Performance

## Contexte projet
- CSS compilé par SASS → `public/assets/css/main.css` (1 fichier, déjà minifié en prod via le pipeline Sass)
- JS : `public/assets/js/main.js` (vanilla, ~70 lignes, aucune dépendance)
- Images servies depuis `public/assets/images/`
- Serveur MAMP local → Apache + PHP 8.1
- Hébergement cible : o2switch (Apache partagé)
- Fonts : DM Sans + DM Serif Display depuis Google Fonts (preconnect déjà en place ✓)

Scores Lighthouse cibles : **Performance ≥ 90, Accessibility ≥ 95, Best Practices = 100, SEO ≥ 95**

---

## 1. Images

### Format et compression
Toujours livrer les images en **WebP** (déjà le cas pour `sonia.webp`).
Pour les thumbnails de projets, fournir deux résolutions via `srcset` :

```html
<!-- project-card__thumb : 160px → 320px max (2x) sur desktop -->
<img src="<?= htmlspecialchars($project['thumbnail']) ?>"
     srcset="<?= htmlspecialchars($project['thumbnail']) ?> 1x,
             <?= htmlspecialchars($project['thumbnail_2x'] ?? $project['thumbnail']) ?> 2x"
     width="560" height="160"
     alt="<?= $title ?>"
     loading="lazy"
     decoding="async">
```

### Lazy loading
- `loading="lazy"` sur **toutes** les images sauf le LCP (hero portrait).
- `loading="eager"` + `fetchpriority="high"` sur `sonia.webp` (LCP).
- `decoding="async"` sur toutes les images non-critiques.

```php
<!-- Hero — LCP prioritaire -->
<img src="/assets/images/sonia.webp"
     alt="Sonia Habibi, développeuse full-stack"
     width="480" height="560"
     loading="eager"
     fetchpriority="high"
     decoding="sync">

<!-- Toute autre image -->
<img src="<?= htmlspecialchars($project['thumbnail']) ?>"
     alt="<?= $title ?>"
     width="560" height="160"
     loading="lazy"
     decoding="async">
```

### Tailles de référence
| Image            | Dimensions | Poids max visé |
|------------------|------------|----------------|
| `sonia.webp`     | 480×560px  | < 80 Ko        |
| Thumbnail projet | 560×200px  | < 40 Ko        |
| og-cover         | 1200×630px | < 120 Ko       |

---

## 2. CSS — CSS critique inline

Le fichier `main.css` fait ~1 ligne minifiée mais contient tout le CSS du site.
Pour éliminer le render-blocking, extraire le CSS **above-the-fold** et l'inliner.

### CSS critique à inliner dans `<head>` (layout `main.php`)
```html
<style>
/* Critical CSS — above the fold uniquement */
:root{--bg:#fff;--bg-soft:#f7f6f4;--text:#111110;--text-2:#5a5956;--text-3:#9a9895;
  --border:rgba(0,0,0,.08);--font-sans:'DM Sans',system-ui,sans-serif;
  --font-serif:'DM Serif Display',Georgia,serif;--nav-h:60px;--max-w:1160px;
  --radius-sm:6px;--radius-md:10px;--radius-lg:14px}
[data-theme=dark]{--bg:#111110;--bg-soft:#1a1918;--text:#f0eeeb;--text-2:#a09e9b}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--font-sans);font-size:16px;line-height:1.6;
  color:var(--text);background:var(--bg);-webkit-font-smoothing:antialiased}
.nav{position:sticky;top:0;z-index:100;background:var(--bg);
  border-bottom:1px solid var(--border);height:var(--nav-h)}
.nav__inner{max-width:var(--max-w);margin:0 auto;padding:0 24px;
  height:100%;display:flex;align-items:center;gap:32px}
.hero{max-width:var(--max-w);margin:0 auto;padding:0 24px;
  display:grid;grid-template-columns:1fr 420px;gap:48px;align-items:center;
  min-height:calc(100vh - var(--nav-h))}
</style>

<!-- Charger le reste en différé -->
<link rel="preload" href="/portfolio/public/assets/css/main.css" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/portfolio/public/assets/css/main.css"></noscript>
```

---

## 3. Fonts — `font-display: swap`

Les Google Fonts sont chargées via `<link>` — ajouter `&display=swap` à l'URL :

```html
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Serif+Display:ital@1&display=swap"
      rel="stylesheet">
```

`display=swap` est déjà dans l'URL Google Fonts — vérifier qu'il est présent.

### Préchargement de la fonte critique (DM Serif Display — logo)
```html
<!-- Dans <head>, avant le CSS -->
<link rel="preload"
      href="https://fonts.gstatic.com/s/dmseriftext/..."
      as="font" type="font/woff2" crossorigin>
```

> Pour connaître l'URL exacte du woff2 : inspecter l'onglet réseau du navigateur après
> que Google Fonts charge, filtrer par `woff2`.

---

## 4. JS — chargement différé

Le script `main.js` est déjà en `defer` dans `layouts/main.php` ✓.

```html
<!-- Déjà correct -->
<script src="/portfolio/public/assets/js/main.js" defer></script>
```

Règles à respecter pour tout nouveau script :
- `defer` si le script n'est pas critique au rendu initial
- `async` jamais (risque de race condition avec le DOM)
- Pas de script inline bloquant dans `<head>`
- Taille cible de `main.js` : < 10 Ko minifié

---

## 5. Cache headers — `.htaccess` dans `public/`

```apache
# Activer la compression Gzip
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript
    AddOutputFilterByType DEFLATE application/javascript application/json
</IfModule>

# Cache statique longue durée
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/webp          "access plus 1 year"
    ExpiresByType image/jpeg          "access plus 1 year"
    ExpiresByType image/png           "access plus 1 year"
    ExpiresByType text/css            "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType font/woff2          "access plus 1 year"
</IfModule>

# Cache-Control headers
<FilesMatch "\.(webp|jpg|png|svg|woff2)$">
    Header set Cache-Control "public, max-age=31536000, immutable"
</FilesMatch>
<FilesMatch "\.(css|js)$">
    Header set Cache-Control "public, max-age=2592000"
</FilesMatch>
```

### Cache busting CSS/JS
Ajouter un query string basé sur le timestamp de modification dans `layouts/main.php` :
```php
<?php $cssV = filemtime(ROOT_PATH . '/public/assets/css/main.css'); ?>
<link rel="stylesheet" href="/portfolio/public/assets/css/main.css?v=<?= $cssV ?>">
```

---

## 6. TTFB — Optimisation PHP

### Activer `ob_gzhandler` dans `public/index.php` (si non géré par Apache)
```php
if (extension_loaded('zlib') && !headers_sent()) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}
```

### Requêtes BDD — éviter les N+1
```php
// ✓ Un seul SELECT pour la home (getFeatured)
// ✓ Un seul SELECT pour la liste (getAll)
// Pas de requête dans les boucles de vues
```

---

## 7. Preload / Prefetch

```html
<!-- Dans layouts/main.php <head> -->

<!-- Précharger la photo hero (LCP) -->
<link rel="preload" href="/assets/images/sonia.webp" as="image">

<!-- Préfetcher la page projets si le visiteur est sur la home -->
<?php if (($currentPage ?? '') === 'home'): ?>
<link rel="prefetch" href="/projets">
<?php endif; ?>
```

---

## 8. Pipeline SASS — production

Le `package.json` a Sass. Ajouter un script de build production minifié :

```json
{
  "scripts": {
    "watch": "sass scss/main.scss public/assets/css/main.css --watch",
    "build": "sass scss/main.scss public/assets/css/main.css --style=compressed --no-source-map"
  }
}
```

Pour minifier `main.js` sans bundler :
```bash
npx terser public/assets/js/main.js -o public/assets/js/main.min.js --compress --mangle
```
Puis pointer vers `main.min.js` dans `layouts/main.php` en production via `$_ENV['APP_ENV']`.

---

## 9. Checklist Lighthouse avant déploiement
- [ ] `loading="eager" fetchpriority="high"` sur `sonia.webp` (LCP)
- [ ] `loading="lazy" decoding="async"` sur toutes les autres images
- [ ] `width` et `height` définis sur tous les `<img>` (évite CLS)
- [ ] Toutes les images en WebP
- [ ] CSS critique inliné, reste en preload différé
- [ ] `display=swap` dans l'URL Google Fonts
- [ ] Gzip activé via `.htaccess`
- [ ] Cache headers longue durée sur assets statiques
- [ ] Cache busting via `?v=filemtime()` sur CSS/JS
- [ ] `main.js` en `defer`
- [ ] Aucun script inline bloquant dans `<head>`
- [ ] `sass --style=compressed` pour le build prod
