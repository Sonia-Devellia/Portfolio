<?php
/**
 * Composant — carte cas d'étude pour la grille.
 *
 * @var array    $project   Entrée du tableau projects.php
 * @var callable $t         Helper de traduction
 */
$frameClass = $project['frame'] === 'desktop' ? 'frame-macbook frame-macbook--sm' : 'frame-iphone frame-iphone--sm';
$imgAttrs   = ['loading' => 'lazy', 'decoding' => 'async'];
?>
<article class="casestudy-card">
    <div class="casestudy-card__visual">
        <div class="<?= $frameClass ?>">
            <?= picture($project['image'], $t($project['title_key']), null, null, $imgAttrs) ?>
        </div>
    </div>
    <div class="casestudy-card__eyebrow">
        <span><?= $t('projects.kind.casestudy') ?> · <?= e($project['year']) ?></span>
        <span><?= $t($project['type_key']) ?></span>
    </div>
    <h3 class="casestudy-card__title"><?= $t($project['title_key']) ?></h3>
    <p class="casestudy-card__pitch"><?= $t($project['pitch_key']) ?></p>
    <div class="casestudy-card__stack">
        <?php foreach ($project['stack'] as $tech): ?>
        <span><?= e($tech) ?></span>
        <?php endforeach; ?>
    </div>
</article>
