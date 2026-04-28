# Skill — SEO On-Page

## Contexte projet
Site portfolio bilingue FR/EN, PHP MVC. URL de base : `https://sonia-habibi.dev` (prod).
La langue active vit dans `$_SESSION['lang']` ('fr' | 'en').
Le layout est `app/Views/layouts/main.php` — c'est là que vont toutes les balises `<head>`.

---

## 1. Balises meta — layout `main.php`

### État actuel vs cible

Le layout a déjà : `<title>`, `<meta description>`, Open Graph basique.
Ce qui manque : `<link rel="canonical">`, hreflang, meta robots, og:image, og:locale.

### Template `<head>` complet cible
```php
<!-- Title — pattern : [Sujet] — Sonia Habibi | Dev Full-Stack -->
<title><?= htmlspecialchars($title ?? 'Sonia Habibi — Dev Full-Stack · PHP · Python · IA') ?></title>

<!-- Meta base -->
<meta name="description" content="<?= htmlspecialchars($metaDesc ?? $t('hero.sub')) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= htmlspecialchars($canonical ?? ($_ENV['APP_URL'] . $_SERVER['REQUEST_URI'])) ?>">

<!-- Hreflang bilingue -->
<link rel="alternate" hreflang="fr" href="<?= $_ENV['APP_URL'] ?><?= strtok($_SERVER['REQUEST_URI'], '?') ?>">
<link rel="alternate" hreflang="en" href="<?= $_ENV['APP_URL'] ?><?= strtok($_SERVER['REQUEST_URI'], '?') ?>">
<link rel="alternate" hreflang="x-default" href="<?= $_ENV['APP_URL'] ?>">

<!-- Open Graph -->
<meta property="og:title"       content="<?= htmlspecialchars($title ?? 'Sonia Habibi — Dev Full-Stack') ?>">
<meta property="og:description" content="<?= htmlspecialchars($metaDesc ?? $t('hero.sub')) ?>">
<meta property="og:url"         content="<?= htmlspecialchars($_ENV['APP_URL'] . $_SERVER['REQUEST_URI']) ?>">
<meta property="og:type"        content="<?= $ogType ?? 'website' ?>">
<meta property="og:image"       content="<?= htmlspecialchars($ogImage ?? $_ENV['APP_URL'] . '/assets/images/og-cover.jpg') ?>">
<meta property="og:locale"      content="<?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'fr_FR' : 'en_GB' ?>">

<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= htmlspecialchars($title ?? 'Sonia Habibi') ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($metaDesc ?? $t('hero.sub')) ?>">
<meta name="twitter:image"       content="<?= htmlspecialchars($ogImage ?? $_ENV['APP_URL'] . '/assets/images/og-cover.jpg') ?>">
```

### Passer les meta depuis les controllers
```php
// ProjectController::show()
$this->render('projects/show', [
    'project'   => $project,
    'title'     => $project['title_' . $lang] . ' — Sonia Habibi',
    'metaDesc'  => mb_substr(strip_tags($project['desc_' . $lang]), 0, 155),
    'canonical' => $_ENV['APP_URL'] . '/projets/' . $project['slug'],
    'ogImage'   => $project['thumbnail'] ? $_ENV['APP_URL'] . $project['thumbnail'] : null,
    'ogType'    => 'article',
]);
```

---

## 2. Données structurées JSON-LD

### Schema `Person` + `WebSite` — à injecter dans `layouts/main.php` (une fois, sur toutes les pages)
```php
<?php
$lang = $_SESSION['lang'] ?? 'fr';
$schemaName = 'Sonia Habibi';
$schemaDesc = $lang === 'fr'
    ? 'Développeuse full-stack freelance spécialisée PHP, Python, JavaScript et IA embarquée.'
    : 'Freelance full-stack developer specialised in PHP, Python, JavaScript and embedded AI.';
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Person",
      "@id": "<?= $_ENV['APP_URL'] ?>#sonia",
      "name": "<?= $schemaName ?>",
      "jobTitle": "<?= $lang === 'fr' ? 'Développeuse Full-Stack Freelance' : 'Freelance Full-Stack Developer' ?>",
      "description": "<?= $schemaDesc ?>",
      "url": "<?= $_ENV['APP_URL'] ?>",
      "image": "<?= $_ENV['APP_URL'] ?>/assets/images/sonia.webp",
      "sameAs": [
        "https://github.com/sonia-habibi",
        "https://www.linkedin.com/in/sonia-habibi",
        "https://www.malt.fr/profile/soniahabibi"
      ],
      "knowsAbout": ["PHP", "Python", "JavaScript", "MySQL", "LLM APIs", "MVC Architecture"]
    },
    {
      "@type": "WebSite",
      "@id": "<?= $_ENV['APP_URL'] ?>#website",
      "url": "<?= $_ENV['APP_URL'] ?>",
      "name": "Sonia Habibi — Dev Full-Stack",
      "author": { "@id": "<?= $_ENV['APP_URL'] ?>#sonia" },
      "inLanguage": ["fr-FR", "en-GB"]
    }
  ]
}
</script>
```

### Schema `SoftwareApplication` — page projet `projects/show.php`
```php
<?php if ($project && !$project['is_wip']): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "<?= htmlspecialchars($project['title_' . ($lang ?? 'fr')]) ?>",
  "description": "<?= htmlspecialchars($project['desc_' . ($lang ?? 'fr')]) ?>",
  "author": { "@id": "<?= $_ENV['APP_URL'] ?>#sonia" },
  "applicationCategory": "WebApplication"
  <?php if ($project['github_url']): ?>
  ,"codeRepository": "<?= htmlspecialchars($project['github_url']) ?>"
  <?php endif; ?>
  <?php if ($project['demo_url']): ?>
  ,"url": "<?= htmlspecialchars($project['demo_url']) ?>"
  <?php endif; ?>
}
</script>
<?php endif; ?>
```

---

## 3. Hiérarchie des headings par page

| Page                  | h1                        | h2                         | h3                    |
|-----------------------|---------------------------|----------------------------|-----------------------|
| `/` (home)            | *(dans hero, implicite)*  | Services, Projets récents, Profil | Titres service-card |
| `/projets`            | "Tous mes projets"        | Titres project-card        | —                     |
| `/projets/{slug}`     | Titre du projet           | —                          | —                     |
| `/contact`            | "Contact"                 | —                          | —                     |

> La home n'a pas de `<h1>` explicite actuellement — le titre hero est un `<p>`.
> Pour le SEO, envisager de promouvoir `.hero__title` en `<h1>`.

---

## 4. `sitemap.xml` — `public/sitemap.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">

  <url>
    <loc>https://sonia-habibi.dev/</loc>
    <xhtml:link rel="alternate" hreflang="fr" href="https://sonia-habibi.dev/"/>
    <xhtml:link rel="alternate" hreflang="en" href="https://sonia-habibi.dev/"/>
    <changefreq>monthly</changefreq>
    <priority>1.0</priority>
  </url>

  <url>
    <loc>https://sonia-habibi.dev/projets</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>

  <!-- Générer dynamiquement depuis la BDD via un controller SitemapController -->
  <!-- GET /sitemap.xml → SitemapController::index() -->

</urlset>
```

### `SitemapController` — génération dynamique
```php
public function index(): void
{
    $projects = Project::getAll();
    header('Content-Type: application/xml; charset=UTF-8');
    // Rendre la vue sans layout
    $this->render('sitemap', ['projects' => $projects], 'layouts/none');
}
```

---

## 5. `robots.txt` — `public/robots.txt`

```
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /admin/login
Disallow: /lang/

Sitemap: https://sonia-habibi.dev/sitemap.xml
```

---

## 6. Performances SEO (Core Web Vitals)

### LCP (Largest Contentful Paint) — cible < 2.5s
- L'image `.hero__img` (`sonia.webp`) est le LCP candidat → `loading="eager"` déjà en place ✓
- Ajouter `fetchpriority="high"` sur cette image.
- Précharger la fonte DM Serif Display (utilisée pour le logo).

### CLS (Cumulative Layout Shift) — cible < 0.1
- Toujours définir `width` et `height` sur les `<img>` pour réserver l'espace.
- Le hero photo a déjà `width="480" height="560"` ✓.
- Les `.project-card__thumb` doivent avoir une hauteur fixe via CSS (160px) ✓.

### INP (Interaction to Next Paint) — cible < 200ms
- Les listeners de `main.js` utilisent `{ passive: true }` pour le scroll ✓.
- Le burger et le theme toggle sont synchrones et légers ✓.

---

## 7. Checklist SEO avant mise en ligne
- [ ] `<title>` unique sur chaque page (< 60 caractères)
- [ ] `<meta description>` unique (< 155 caractères)
- [ ] `<link rel="canonical">` présent
- [ ] JSON-LD Person + WebSite injecté
- [ ] `og:image` défini (1200×630px minimum)
- [ ] `sitemap.xml` accessible et soumis à Google Search Console
- [ ] `robots.txt` bloque `/admin/`
- [ ] `sonia.webp` a `fetchpriority="high"`
- [ ] Toutes les images ont `width` et `height`
- [ ] Pas de contenu dupliqué FR/EN (hreflang configurés)
