<?php
/**
 * Composant — carte cas d'étude pour la grille.
 *
 * @var array    $project   Entrée du tableau projects.php
 * @var callable $t         Helper de traduction
 * @var string   $base      URL de base
 */
$base = $base ?? '';
?>
<article class="casestudy-card">
    <div class="casestudy-card__visual">
        <?php if ($project['frame'] === 'desktop'): ?>
        <div class="frame-macbook frame-macbook--sm">
            <img src="<?= $base . htmlspecialchars($project['image']) ?>"
                 alt="<?= $t($project['title_key']) ?>"
                 loading="lazy"
                 decoding="async">
        </div>
        <?php else: ?>
        <div class="frame-iphone frame-iphone--sm">
            <img src="<?= $base . htmlspecialchars($project['image']) ?>"
                 alt="<?= $t($project['title_key']) ?>"
                 loading="lazy"
                 decoding="async">
        </div>
        <?php endif; ?>
    </div>
    <div class="casestudy-card__eyebrow">
        <span><?= $t('projects.kind.casestudy') ?> · <?= htmlspecialchars($project['year']) ?></span>
        <span><?= $t($project['type_key']) ?></span>
    </div>
    <h3 class="casestudy-card__title"><?= $t($project['title_key']) ?></h3>
    <p class="casestudy-card__pitch"><?= $t($project['pitch_key']) ?></p>
    <div class="casestudy-card__stack">
        <?php foreach ($project['stack'] as $tech): ?>
        <span><?= htmlspecialchars($tech) ?></span>
        <?php endforeach; ?>
    </div>
</article>
