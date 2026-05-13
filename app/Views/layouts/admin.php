<?php
/**
 * Layout admin — interface FR-only (un seul utilisateur).
 *
 * @var string $content
 * @var string $title
 */
$base = base_url();
$uri  = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin — Sonia Habibi') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Serif+Display:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/main.css">
</head>
<body class="admin-body">

<?php if (!empty($_SESSION['admin'])): ?>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <a href="<?= $base ?>/admin/projets" class="admin-sidebar__logo">Sonia</a>
        <nav class="admin-sidebar__nav" aria-label="Navigation administration">
            <a href="<?= $base ?>/admin/projets"
               class="admin-sidebar__link <?= str_contains($uri, '/projets') ? 'admin-sidebar__link--active' : '' ?>">Projets</a>
            <a href="<?= $base ?>/" class="admin-sidebar__link admin-sidebar__link--external"
               target="_blank" rel="noopener">Voir le site ↗</a>
        </nav>
        <form method="post" action="<?= $base ?>/admin/logout" class="admin-sidebar__logout-form">
            <?= csrf_field() ?>
            <button type="submit" class="admin-sidebar__logout">Déconnexion</button>
        </form>
    </aside>
    <main class="admin-main" id="main">
        <?= $content ?>
    </main>
</div>
<?php else: ?>
<main id="main"><?= $content ?></main>
<?php endif; ?>

<script src="<?= $base ?>/assets/js/main.js" defer></script>
</body>
</html>
