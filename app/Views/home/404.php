<?php
/**
 * Vue — erreur 404.
 *
 * @var callable(string): string $t  Helper de traduction
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
$t ??= static fn(string $k): string => $k;
?>
<section style="max-width: 480px; margin: 120px auto; padding: 0 24px; text-align: center;">
    <p class="eyebrow"><?= $t('404.title') ?></p>
    <h1 style="font-size: 80px; font-weight: 500; letter-spacing: -0.04em; color: var(--text); line-height: 1; margin-bottom: 16px;">404</h1>
    <p style="font-size: 15px; color: var(--text-2); margin-bottom: 28px;"><?= $t('404.sub') ?></p>
    <a href="<?= $base ?>/" class="btn btn--dark"><?= $t('404.back') ?></a>
</section>
