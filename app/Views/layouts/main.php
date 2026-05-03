<?php
/**
 * @var callable(string): string $t
 * @var string $content
 * @var string $title
 * @var string|null $metaDesc
 * @var string|null $canonical
 * @var string|null $ogImage
 * @var string|null $ogType
 * @var array<string> $scripts
 */
$base      = rtrim($_ENV['APP_URL'] ?? '', '/');
$appUrl    = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
$lang      = $_SESSION['lang'] ?? 'fr';
$pageTitle = htmlspecialchars($title ?? 'Sonia Habibi — Dev Full-Stack');
$pageDesc  = htmlspecialchars($metaDesc ?? $t('hero.sub'));
$pageUrl   = htmlspecialchars($canonical ?? ($appUrl . strtok($_SERVER['REQUEST_URI'] ?? '/', '?')));
$pageOgImg = htmlspecialchars($ogImage   ?? ($appUrl . '/assets/images/og-cover.jpg'));
$pageType  = htmlspecialchars($ogType    ?? 'website');
$ogLocale  = $lang === 'fr' ? 'fr_FR' : 'en_GB';

$schemaDesc = $lang === 'fr'
    ? 'Développeuse full-stack freelance spécialisée PHP, Python, JavaScript et IA embarquée.'
    : 'Freelance full-stack developer specialised in PHP, Python, JavaScript and embedded AI.';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title & meta -->
    <title><?= $pageTitle ?></title>
    <meta name="description" content="<?= $pageDesc ?>">
    <meta name="author" content="Sonia Habibi">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $pageUrl ?>">

    <!-- Hreflang bilingue -->
    <link rel="alternate" hreflang="fr"      href="<?= $pageUrl ?>">
    <link rel="alternate" hreflang="en"      href="<?= $pageUrl ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($appUrl) ?>">

    <!-- Open Graph -->
    <meta property="og:title"       content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= $pageDesc ?>">
    <meta property="og:url"         content="<?= $pageUrl ?>">
    <meta property="og:type"        content="<?= $pageType ?>">
    <meta property="og:image"       content="<?= $pageOgImg ?>">
    <meta property="og:locale"      content="<?= $ogLocale ?>">
    <meta property="og:site_name"   content="Sonia Habibi — Dev Full-Stack">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= $pageTitle ?>">
    <meta name="twitter:description" content="<?= $pageDesc ?>">
    <meta name="twitter:image"       content="<?= $pageOgImg ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Serif+Display:ital@1&display=swap" rel="stylesheet">

    <?php $cssV = @filemtime(($_SERVER['DOCUMENT_ROOT'] ?? '') . '/portfolio/public/assets/css/main.css') ?: '1'; ?>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/main.css?v=<?= $cssV ?>">

    <!-- JSON-LD — Person + WebSite + ProfessionalService -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Person",
          "@id": "<?= htmlspecialchars($appUrl) ?>#sonia",
          "name": "Sonia Habibi",
          "jobTitle": "<?= $lang === 'fr' ? 'Développeuse Full-Stack Freelance' : 'Freelance Full-Stack Developer' ?>",
          "description": "<?= htmlspecialchars($schemaDesc) ?>",
          "url": "<?= htmlspecialchars($appUrl) ?>",
          "image": "<?= htmlspecialchars($appUrl) ?>/assets/images/sonia.webp",
          "sameAs": [
            "https://github.com/sonia-habibi",
            "https://www.linkedin.com/in/sonia-habibi",
            "https://www.malt.fr/profile/soniahabibi"
          ],
          "knowsAbout": ["PHP", "Python", "JavaScript", "MySQL", "LLM APIs", "MVC Architecture"]
        },
        {
          "@type": "WebSite",
          "@id": "<?= htmlspecialchars($appUrl) ?>#website",
          "url": "<?= htmlspecialchars($appUrl) ?>",
          "name": "Sonia Habibi — Dev Full-Stack",
          "author": { "@id": "<?= htmlspecialchars($appUrl) ?>#sonia" },
          "inLanguage": ["fr-FR", "en-GB"]
        },
        {
          "@type": "ProfessionalService",
          "@id": "<?= htmlspecialchars($appUrl) ?>#service",
          "name": "<?= $lang === 'fr' ? 'Sonia Habibi — Développement web freelance' : 'Sonia Habibi — Freelance Web Development' ?>",
          "description": "<?= htmlspecialchars($schemaDesc) ?>",
          "provider": { "@id": "<?= htmlspecialchars($appUrl) ?>#sonia" },
          "areaServed": ["FR", "EU", "Worldwide remote"],
          "serviceType": ["<?= $lang === 'fr' ? 'Développement web full-stack' : 'Full-stack web development' ?>", "Intégration API IA (Claude, OpenAI)", "MVP & prototypes", "Audit et sécurisation PHP"],
          "url": "<?= htmlspecialchars($appUrl) ?>",
          "image": "<?= htmlspecialchars($appUrl) ?>/assets/images/sonia.webp"
        }
      ]
    }
    </script>
</head>
<body>

<a href="#main" class="skip-link"><?= $lang === 'fr' ? 'Aller au contenu principal' : 'Skip to main content' ?></a>

<div class="cursor-dot"  id="cursorDot"  aria-hidden="true"></div>
<div class="cursor-ring" id="cursorRing" aria-hidden="true"></div>

<!-- ─── NAVIGATION ──────────────────────────────────────── -->
<header class="nav" id="nav">
    <div class="nav__inner">

        <a href="<?= $base ?>/" class="nav__logo">Sonia</a>

        <nav class="nav__links" aria-label="<?= $t('a11y.nav.main') ?>">
            <a href="<?= $base ?>/#services"><?= $t('nav.services') ?></a>
            <a href="<?= $base ?>/projets"><?= $t('nav.projects') ?></a>
            <a href="<?= $base ?>/#about"><?= $t('nav.about') ?></a>
            <a href="<?= $base ?>/contact"><?= $t('nav.contact') ?></a>
        </nav>

        <div class="nav__actions">
            <!-- Disponibilité -->
            <span class="nav__avail">
                <span class="nav__avail-dot"></span>
                <?= $t('nav.available') ?>
            </span>

            <!-- Switcher langue -->
            <div class="lang-switch" aria-label="<?= $t('a11y.lang.switch') ?>">
                <?php $lang = $_SESSION['lang'] ?? 'fr'; ?>
                <a href="<?= $base ?>/lang/fr" class="lang-switch__btn <?= $lang === 'fr' ? 'is-active' : '' ?>">FR</a>
                <span class="lang-switch__sep">/</span>
                <a href="<?= $base ?>/lang/en" class="lang-switch__btn <?= $lang === 'en' ? 'is-active' : '' ?>">EN</a>
            </div>

            <!-- Dark mode toggle -->
            <button class="theme-toggle" id="themeToggle" aria-label="<?= $t('a11y.theme.toggle') ?>">
                <svg class="theme-toggle__sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg class="theme-toggle__moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            <!-- CTA mobile / desktop -->
            <a href="<?= $base ?>/contact" class="btn btn--dark nav__cta"><?= $t('nav.contact') ?> →</a>
        </div>

        <!-- Burger mobile -->
        <button class="nav__burger" id="navBurger" aria-label="<?= $t('a11y.menu') ?>" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<!-- ─── CONTENU PRINCIPAL ───────────────────────────────── -->
<main id="main">
    <?= $content ?>
</main>

<!-- ─── FOOTER ──────────────────────────────────────────── -->
<footer class="footer">
    <div class="footer__inner">
        <a href="<?= $base ?>/" class="footer__logo">Sonia</a>

        <div class="footer__links">
            <a href="https://www.malt.fr" target="_blank" rel="noopener">Malt</a>
            <a href="https://linkedin.com" target="_blank" rel="noopener">LinkedIn</a>
            <a href="https://github.com/sonia-habibi" target="_blank" rel="noopener">GitHub</a>
        </div>

        <p class="footer__copy">
            <?= $t('footer.rights') ?> · <?= $t('footer.location') ?>
        </p>
    </div>
</footer>

<script src="<?= $base ?>/assets/js/main.js" defer></script>

<?php if (!empty($scripts)): ?>
    <?php foreach ($scripts as $src): ?>
        <script src="<?= htmlspecialchars($src) ?>" defer></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
