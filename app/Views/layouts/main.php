<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'fr') ?>" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sonia Habibi — Dev Full-Stack') ?></title>
    <meta name="description" content="<?= $t('hero.sub') ?>">
    <meta name="author" content="Sonia Habibi">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Sonia Habibi') ?>">
    <meta property="og:description" content="<?= $t('hero.sub') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($_ENV['APP_URL'] ?? '') ?>">
    <meta property="og:type" content="website">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Serif+Display:ital@1&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= url('/assets/css/main.css') ?>">
</head>
<body>

<!-- ─── NAVIGATION ──────────────────────────────────────── -->
<header class="nav" id="nav">
    <div class="nav__inner">

        <a href="<?= url('/') ?>" class="nav__logo">Sonia</a>

        <nav class="nav__links" aria-label="Navigation principale">
            <a href="<?= url('/#services') ?>"><?= $t('nav.services') ?></a>
            <a href="<?= url('/projets') ?>"><?= $t('nav.projects') ?></a>
            <a href="<?= url('/#about') ?>"><?= $t('nav.about') ?></a>
            <a href="<?= url('/contact') ?>"><?= $t('nav.contact') ?></a>
        </nav>

        <div class="nav__actions">
            <!-- Disponibilité -->
            <span class="nav__avail">
                <span class="nav__avail-dot"></span>
                <?= $t('nav.available') ?>
            </span>

            <!-- Switcher langue -->
            <div class="lang-switch" aria-label="Langue">
                <?php $lang = $_SESSION['lang'] ?? 'fr'; ?>
                <a href="<?= url('/lang/fr') ?>" class="lang-switch__btn <?= $lang === 'fr' ? 'is-active' : '' ?>">FR</a>
                <span class="lang-switch__sep">/</span>
                <a href="<?= url('/lang/en') ?>" class="lang-switch__btn <?= $lang === 'en' ? 'is-active' : '' ?>">EN</a>
            </div>

            <!-- Dark mode toggle -->
            <button class="theme-toggle" id="themeToggle" aria-label="Changer le thème">
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
            <a href="<?= url('/contact') ?>" class="btn btn--dark nav__cta"><?= $t('nav.contact') ?> →</a>
        </div>

        <!-- Burger mobile -->
        <button class="nav__burger" id="navBurger" aria-label="Menu" aria-expanded="false">
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
        <a href="<?= url('/') ?>" class="footer__logo">Sonia</a>

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

<script src="<?= url('/assets/js/main.js') ?>" defer></script>

<?php if (!empty($scripts)): ?>
    <?php foreach ($scripts as $src): ?>
        <script src="<?= htmlspecialchars($src) ?>" defer></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
