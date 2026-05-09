<?php
/**
 * Partial — section services (2 piliers).
 *
 * @var callable(string): string $t
 * @var string $base
 */
?>
<section class="section services" id="services">
    <span class="section__watermark" aria-hidden="true">01</span>
    <div class="section__inner">

        <div class="section__head">
            <div>
                <p class="eyebrow"><?= $t('services.eyebrow') ?></p>
                <h2 class="section__title"><?= $t('services.title') ?></h2>
            </div>
        </div>
        <p class="services__sub"><?= $t('services.sub') ?></p>

        <div class="services__pillars">

            <?php foreach (['p1', 'p2'] as $p): ?>
            <article class="pillar pillar--<?= $p ?> reveal">
                <header class="pillar__head">
                    <p class="pillar__num"><?= $t("services.{$p}.num") ?></p>
                    <h3 class="pillar__title"><?= $t("services.{$p}.title") ?></h3>
                    <p class="pillar__sub"><?= $t("services.{$p}.sub") ?></p>
                </header>
                <ul class="pillar__list" role="list">
                    <?php foreach (['s1', 's2', 's3', 's4'] as $s): ?>
                    <li class="pillar__item">
                        <strong class="pillar__item-title"><?= $t("services.{$p}.{$s}.t") ?></strong>
                        <span class="pillar__item-desc"><?= $t("services.{$p}.{$s}.d") ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </article>
            <?php endforeach; ?>

        </div>
    </div>
</section>
