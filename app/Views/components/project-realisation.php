<?php
/**
 * Composant — bloc full-bleed pour une réalisation.
 *
 * @var array    $project   Entrée du tableau projects.php
 * @var callable $t         Helper de traduction
 * @var string   $base      URL de base
 * @var bool     $reversed  Alterner image/texte
 */
$reversed = $reversed ?? false;
$base     = $base     ?? '';
?>
<article class="realisation <?= $reversed ? 'realisation--reversed' : '' ?>">
    <div class="realisation__visual">
        <?php if ($project['frame'] === 'desktop'): ?>
        <div class="frame-macbook">
            <img src="<?= $base . htmlspecialchars($project['image']) ?>"
                 alt="<?= $t($project['title_key']) ?>"
                 loading="lazy"
                 decoding="async">
        </div>
        <?php else: ?>
        <div class="frame-iphone">
            <img src="<?= $base . htmlspecialchars($project['image']) ?>"
                 alt="<?= $t($project['title_key']) ?>"
                 loading="lazy"
                 decoding="async">
        </div>
        <?php endif; ?>
    </div>
    <div class="realisation__content">
        <div class="realisation__eyebrow">
            <span><?= $t('projects.kind.realisation') ?> · <?= htmlspecialchars($project['year']) ?></span>
            <span><?= $t($project['type_key']) ?></span>
        </div>
        <h2 class="realisation__title"><?= $t($project['title_key']) ?></h2>
        <p class="realisation__pitch"><?= $t($project['pitch_key']) ?></p>
        <?php if ($project['body_key']): ?>
        <p class="realisation__body"><?= $t($project['body_key']) ?></p>
        <?php endif; ?>
        <div class="realisation__stack">
            <?php foreach ($project['stack'] as $tech): ?>
            <span><?= htmlspecialchars($tech) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</article>
