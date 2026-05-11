<?php
/**
 * Vue — page projets : réalisations + cas d'études.
 *
 * @var callable              $t             Helper de traduction
 * @var array<int, array>     $realisations  Projets livrés
 * @var array<int, array>     $casestudies   Cas d'études personnels
 */
$base         = rtrim($_ENV['APP_URL'] ?? '', '/');
$t          ??= static fn(string $k): string => $k;
$realisations ??= [];
$casestudies  ??= [];
?>

<!-- ─── HERO ────────────────────────────────────────────── -->
<section class="projects-hero">
    <div class="projects-hero__inner">
        <p class="eyebrow"><?= $t('projects.eyebrow') ?></p>
        <h1 class="projects-hero__title">
            <?= $t('projects.hero.title') ?><br>
            <em><?= $t('projects.hero.title_em') ?></em>
        </h1>
        <p class="projects-hero__lede"><?= $t('projects.intro.lede') ?></p>
    </div>
</section>

<!-- ─── RÉALISATIONS ─────────────────────────────────────── -->
<section class="projects-realisations">
    <div class="projects-realisations__inner">
        <header class="projects-section-header">
            <p class="eyebrow"><?= $t('projects.realisations.title') ?></p>
            <span class="projects-section-header__kicker"><?= $t('projects.realisations.kicker') ?></span>
        </header>
        <?php foreach ($realisations as $i => $project):
            $reversed = ($i % 2 !== 0);
        ?>
        <?php include __DIR__ . '/../components/project-realisation.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- ─── CAS D'ÉTUDES ─────────────────────────────────────── -->
<section class="projects-casestudies">
    <div class="projects-casestudies__inner">
        <header class="projects-section-header">
            <p class="eyebrow"><?= $t('projects.casestudies.title') ?></p>
        </header>
        <p class="projects-casestudies__lede"><?= $t('projects.casestudies.lede') ?></p>
        <div class="casestudies-grid">
            <?php foreach ($casestudies as $project): ?>
            <?php include __DIR__ . '/../components/project-casestudy-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── CTA GITHUB ───────────────────────────────────────── -->
<section class="projects-cta">
    <div class="projects-cta__inner">
        <p class="projects-cta__title"><?= $t('projects.cta.title') ?></p>
        <p class="projects-cta__lede"><?= $t('projects.cta.lede') ?></p>
        <a href="https://github.com/Sonia-Devellia"
           class="btn btn--dark"
           target="_blank"
           rel="noopener"
           aria-label="<?= $t('projects.cta.button') ?> (nouvel onglet)">
            <?= $t('projects.cta.button') ?> →
        </a>
    </div>
</section>
