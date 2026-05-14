<?php
/**
 * Layout principal — site public bilingue FR/EN.
 *
 * @var callable(string): string $t
 * @var string                   $content
 * @var string                   $title
 * @var string|null              $metaDesc
 * @var string|null              $canonical
 * @var string|null              $ogImage
 * @var string|null              $ogType
 * @var array|null               $faqSchema
 * @var array|null               $breadcrumbSchema
 * @var array|null               $extraSchemas
 * @var array<string>|null       $scripts
 */
$base   = base_url();
$lang   = $_SESSION['lang'] ?? 'fr';
$isFr   = $lang === 'fr';

$pageTitle = $title ?? ($isFr
    ? 'Sonia Habibi — Développeuse Full-Stack & IA'
    : 'Sonia Habibi — Full-Stack & AI Developer');
$pageDesc  = $metaDesc  ?? $t('hero.sub');
$pageUrl   = $canonical ?? ($base . strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
$pageImg   = $ogImage   ?? ($base . '/assets/images/og-cover.jpg');
$pageType  = $ogType    ?? 'website';

$schemaDesc = $isFr
    ? 'Développeuse full-stack freelance spécialisée PHP, Python, JavaScript et intégrations IA utiles.'
    : 'Freelance full-stack developer specialised in PHP, Python, JavaScript and useful AI integrations.';


$personSchema = [
    '@context' => 'https://schema.org',
    '@type'    => 'Person',
    '@id'      => $base . '#sonia',
    'name'     => 'Sonia Habibi',
    'jobTitle' => $isFr ? 'Développeuse Full-Stack Freelance' : 'Freelance Full-Stack Developer',
    'description' => $schemaDesc,
    'url'      => $base,
    'image'    => $base . '/assets/images/sonia.webp',
    'sameAs'   => [
        'https://github.com/Sonia-Devellia',
        'https://www.linkedin.com/in/sonia-habibi/',
        'https://www.malt.fr/profile/soniahabibi',
    ],
    'knowsAbout' => ['PHP', 'Python', 'JavaScript', 'MySQL', 'LLM APIs', 'MVC Architecture'],
    'workLocation' => ['@type' => 'VirtualLocation', 'name' => 'Remote — France, Suisse, Belgique'],
];

$siteSchema = [
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    '@id'      => $base . '#website',
    'url'      => $base,
    'name'     => 'Sonia Habibi — Dev Full-Stack',
    'author'   => ['@id' => $base . '#sonia'],
    'inLanguage' => ['fr-FR', 'en-GB'],
];

$jsonLd = static fn(array $data): string => json_encode(
    $data,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
);
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDesc) ?>">
    <meta name="author" content="Sonia Habibi">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e($pageUrl) ?>">

    <link rel="alternate" hreflang="fr"        href="<?= e($base . '/lang/fr') ?>">
    <link rel="alternate" hreflang="en"        href="<?= e($base . '/lang/en') ?>">
    <link rel="alternate" hreflang="x-default" href="<?= e($base . '/') ?>">

    <!-- Open Graph -->
    <meta property="og:title"       content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDesc) ?>">
    <meta property="og:url"         content="<?= e($pageUrl) ?>">
    <meta property="og:type"        content="<?= e($pageType) ?>">
    <meta property="og:image"       content="<?= e($pageImg) ?>">
    <meta property="og:locale"      content="<?= $isFr ? 'fr_FR' : 'en_GB' ?>">
    <meta property="og:site_name"   content="Sonia Habibi — Dev Full-Stack">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($pageDesc) ?>">
    <meta name="twitter:image"       content="<?= e($pageImg) ?>">

    <?php
    // Preload du LCP — préfère AVIF si disponible, sinon WebP.
    $heroAvif = ROOT_PATH . '/public/assets/images/sonia.avif';
    $heroSrc  = is_file($heroAvif) ? '/assets/images/sonia.avif' : '/assets/images/sonia.webp';
    $heroType = is_file($heroAvif) ? 'image/avif' : 'image/webp';
    ?>
    <link rel="preload" href="<?= $base . $heroSrc ?>" as="image" type="<?= $heroType ?>" fetchpriority="high">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Serif+Display:ital@1&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('/assets/css/main.css') ?>">

    <!-- JSON-LD -->
    <script type="application/ld+json"><?= $jsonLd($personSchema) ?></script>
    <script type="application/ld+json"><?= $jsonLd($siteSchema) ?></script>

    <?php foreach (array_filter([$faqSchema ?? null, $breadcrumbSchema ?? null]) as $schema): ?>
    <script type="application/ld+json"><?= $jsonLd($schema) ?></script>
    <?php endforeach; ?>

    <?php foreach (($extraSchemas ?? []) as $schema): ?>
    <script type="application/ld+json"><?= $jsonLd($schema) ?></script>
    <?php endforeach; ?>
</head>
<body>

<a href="#main" class="skip-link"><?= $isFr ? 'Aller au contenu principal' : 'Skip to main content' ?></a>

<div class="cursor-dot"  id="cursorDot"  aria-hidden="true"></div>
<div class="cursor-ring" id="cursorRing" aria-hidden="true">
    <span class="cursor-ring__label" aria-hidden="true"></span>
</div>

<header class="nav" id="nav">
    <div class="nav__inner">
        <a href="<?= $base ?>/" class="nav__logo">Sonia</a>

        <nav class="nav__links" aria-label="<?= $t('a11y.nav.main') ?>">
            <a href="<?= $base ?>/projets"><?= $t('nav.projects') ?></a>
            <a href="<?= $base ?>/tarifs"><?= $isFr ? 'Tarifs' : 'Rates' ?></a>
            <a href="<?= $base ?>/contact"><?= $t('nav.contact') ?></a>
        </nav>

        <div class="nav__actions">
            <span class="nav__avail">
                <span class="nav__avail-dot"></span>
                <?= $t('nav.available') ?>
            </span>

            <div class="lang-switch" aria-label="<?= $t('a11y.lang.switch') ?>">
                <a href="<?= $base ?>/lang/fr" class="lang-switch__btn <?= $isFr ? 'is-active' : '' ?>">FR</a>
                <span class="lang-switch__sep">/</span>
                <a href="<?= $base ?>/lang/en" class="lang-switch__btn <?= !$isFr ? 'is-active' : '' ?>">EN</a>
            </div>

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

            <a href="<?= $base ?>/contact" class="btn btn--dark nav__cta"><?= $t('nav.contact') ?> →</a>
        </div>
    </div>
</header>

<main id="main">
    <?= $content ?>
</main>

<footer class="footer">
    <div class="footer__inner">
        <a href="<?= $base ?>/" class="footer__logo">Sonia</a>

        <div class="footer__links">
            <a href="https://www.malt.fr/profile/soniahabibi" target="_blank" rel="noopener noreferrer">Malt</a>
            <a href="https://www.linkedin.com/in/sonia-habibi" target="_blank" rel="noopener noreferrer">LinkedIn</a>
            <a href="https://github.com/Sonia-Devellia" target="_blank" rel="noopener noreferrer">GitHub</a>
        </div>

        <p class="footer__copy"><?= $t('footer.rights') ?> · <?= $t('footer.location') ?></p>

        <span class="footer__time">
            <span class="footer__time-label"><?= $isFr ? 'Heure locale · ' : 'Local time · ' ?></span>
            <span id="localTime"></span>
        </span>
    </div>

    <!-- ─── Maillage SEO local — villes phares ───────────────── -->
    <nav class="footer__zones" aria-label="<?= $isFr ? 'Zones d\'intervention' : 'Areas served' ?>">
        <span class="footer__zones-label"><?= $isFr ? 'Disponible à' : 'Available in' ?></span>
        <a href="<?= $base ?>/dev-freelance/paris">Paris</a>
        <span aria-hidden="true">·</span>
        <a href="<?= $base ?>/dev-freelance/vannes">Vannes</a>
        <span aria-hidden="true">·</span>
        <a href="<?= $base ?>/dev-freelance/rennes">Rennes</a>
        <span aria-hidden="true">·</span>
        <a href="<?= $base ?>/dev-freelance/nantes">Nantes</a>
        <span aria-hidden="true">·</span>
        <a href="<?= $base ?>/dev-freelance/bordeaux">Bordeaux</a>
        <span aria-hidden="true">·</span>
        <a href="<?= $base ?>/dev-freelance/toulouse">Toulouse</a>
        <span aria-hidden="true">·</span>
        <a href="<?= $base ?>/dev-freelance/geneve"><?= $isFr ? 'Genève' : 'Geneva' ?></a>
        <span aria-hidden="true">·</span>
        <a href="<?= $base ?>/dev-freelance/luxembourg">Luxembourg</a>
    </nav>
</footer>

<script src="<?= asset('/assets/js/modules/reveal.js') ?>" defer></script>
<script src="<?= asset('/assets/js/modules/typewriter.js') ?>" defer></script>
<script src="<?= asset('/assets/js/main.js') ?>" defer></script>

<?php foreach (($scripts ?? []) as $src): ?>
<script src="<?= e($src) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
