<?php
/**
 * Partial — section méthode (4 étapes du brief au livrable).
 *
 * @var callable(string): string $t
 */
?>
<section class="section method" id="method">
    <span class="section__watermark" aria-hidden="true">04</span>
    <div class="section__inner">
        <header class="method__head">
            <p class="eyebrow"><?= $t('method.eyebrow') ?></p>
            <h2 class="section__title"><?= $tRaw('method.title') ?></h2>
            <p class="section__sub"><?= $t('method.sub') ?></p>
        </header>
        <ol class="method__steps">
            <?php foreach (['s1', 's2', 's3', 's4'] as $s): ?>
            <li class="method__step">
                <span class="method__num"><?= $t("method.{$s}.num") ?></span>
                <h3 class="method__step-title"><?= $t("method.{$s}.t") ?></h3>
                <p class="method__step-body"><?= $t("method.{$s}.d") ?></p>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
