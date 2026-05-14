<?php
/**
 * Composant — bloc full-bleed pour une réalisation.
 *
 * @var array    $project   Entrée du tableau projects.php
 * @var callable $t         Helper de traduction
 * @var bool     $reversed  Alterner image/texte
 */
$reversed ??= false;

$frameClass = $project['frame'] === 'desktop' ? 'frame-macbook' : 'frame-iphone';
$imgAttrs   = ['loading' => 'lazy', 'decoding' => 'async'];
?>
<article class="realisation <?= $reversed ? 'realisation--reversed' : '' ?>">
    <div class="realisation__visual">
        <div class="<?= $frameClass ?>">
            <?= picture($project['image'], $t($project['title_key']), null, null, $imgAttrs) ?>
        </div>
    </div>
    <div class="realisation__content">
        <div class="realisation__eyebrow">
            <span><?= $t('projects.kind.realisation') ?> · <?= e($project['year']) ?></span>
            <span><?= $t($project['type_key']) ?></span>
        </div>
        <h2 class="realisation__title"><?= $t($project['title_key']) ?></h2>
        <p class="realisation__pitch"><?= $t($project['pitch_key']) ?></p>
        <?php if ($project['body_key']): ?>
        <p class="realisation__body"><?= $t($project['body_key']) ?></p>
        <?php endif; ?>
        <div class="realisation__stack">
            <?php foreach ($project['stack'] as $tech): ?>
            <span><?= e($tech) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</article>
