<?php
/**
 * Layout admin — interface FR-only par choix produit (un seul utilisateur).
 * Les vues admin/* contiennent du texte FR hardcodé volontairement.
 *
 * @var string $content
 * @var string $title
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin — Sonia Habibi') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Serif+Display:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/main.css">
</head>
<body class="admin-body">

<?php if (!empty($_SESSION['admin'])): ?>
<?php $uri = $_SERVER['REQUEST_URI'] ?? ''; ?>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <a href="<?= $base ?>/admin/projets" class="admin-sidebar__logo">Sonia</a>
        <nav class="admin-sidebar__nav" aria-label="Navigation administration">
            <a href="<?= $base ?>/admin/projets"
               class="admin-sidebar__link <?= str_contains($uri, '/projets') ? 'admin-sidebar__link--active' : '' ?>">Projets</a>
            <a href="<?= $base ?>/admin/messages"
               class="admin-sidebar__link <?= str_contains($uri, '/messages') ? 'admin-sidebar__link--active' : '' ?>">Messages</a>
            <a href="<?= $base ?>/" class="admin-sidebar__link admin-sidebar__link--external"
               target="_blank" rel="noopener">Voir le site ↗</a>
        </nav>
        <a href="<?= $base ?>/admin/logout" class="admin-sidebar__logout">Déconnexion</a>
    </aside>
    <main class="admin-main" id="main">
        <?= $content ?>
    </main>
</div>
<?php else: ?>
<main id="main">
    <?= $content ?>
</main>
<?php endif; ?>

<script src="<?= $base ?>/assets/js/main.js" defer></script>
</body>
</html>
