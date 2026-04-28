<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?></title>
    <link rel="stylesheet" href="<?= url('/assets/css/main.css') ?>">
</head>
<body class="admin-body">

<header class="admin-header">
    <div class="admin-header__inner">
        <a href="<?= url('/admin/projets') ?>" class="admin-header__logo">Sonia · Admin</a>
        <?php if (!empty($_SESSION['admin'])): ?>
        <nav class="admin-header__nav">
            <a href="<?= url('/admin/projets') ?>" class="admin-header__link">Projets</a>
            <a href="<?= url('/admin/projets/new') ?>" class="btn btn--dark btn--sm">+ Nouveau</a>
            <a href="<?= url('/admin/logout') ?>" class="btn btn--outline btn--sm">Déconnexion</a>
        </nav>
        <?php endif; ?>
    </div>
</header>

<main class="admin-main">
    <?= $content ?>
</main>

<script src="<?= url('/assets/js/main.js') ?>" defer></script>
</body>
</html>
