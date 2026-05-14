<?php
/**
 * Vue — page d'accueil.
 *
 * @var callable(string): string                  $t
 * @var callable(string): string                  $tRaw
 * @var array<int, array<string, mixed>>          $projects
 * @var array<int, array<string, mixed>>          $stack
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
$t    ??= static fn(string $k): string => $k;
$tRaw ??= static fn(string $k): string => $k;
$projects ??= [];
$stack    ??= [];
$lang = $_SESSION['lang'] ?? 'fr';

$faqItems = ['1', '2', '3', '4', '5', '6', '7', '8'];
?>

<!-- ─── HERO ────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero__content">
        <p class="hero__avail" aria-hidden="true">
            <span></span><?= $t('nav.available') ?>
        </p>
        <p class="eyebrow"><?= $t('hero.eyebrow') ?></p>

        <h1 class="hero__title" data-typewriter aria-label="<?= htmlspecialchars(strip_tags($tRaw('hero.title')), ENT_QUOTES) ?>"><?= $tRaw('hero.title') ?></h1>

        <p class="hero__sub" id="heroSub"><?= $t('hero.sub') ?></p>
        <div class="hero__actions">
            <a href="<?= $base ?>/case-studies/amanea-voyages" class="btn btn--dark"><?= $t('hero.cta_case') ?></a>
            <a href="<?= $base ?>/contact" class="btn btn--outline"><?= $t('hero.cta_contact') ?></a>
        </div>
        <div class="hero__tags">
            <span class="tag tag--blue">PHP</span>
            <span class="tag tag--green">Python</span>
            <span class="tag tag--amber">JavaScript</span>
            <span class="tag tag--purple">LLM</span>
        </div>
    </div>
    <div class="hero__photo">
        <?= picture('/assets/images/sonia.webp', $t('hero.img.alt'), 480, 560, [
            'class'         => 'hero__img',
            'loading'       => 'eager',
            'fetchpriority' => 'high',
            'decoding'      => 'sync',
        ]) ?>
        <div class="hero__nameplate">
            <span class="hero__nameplate-name">Sonia Habibi</span>
            <span class="hero__nameplate-role"><?= $t('hero.nameplate.role') ?></span>
        </div>
    </div>
</section>

<!-- ─── SERVICES ────────────────────────────────────────── -->
<section class="section services" id="services">
    <span class="section__watermark" aria-hidden="true">01</span>
    <div class="section__inner">

        <div class="section__head">
            <div>
                <p class="eyebrow"><?= $t('services.eyebrow') ?></p>
                <h2 class="section__title"><?= $tRaw('services.title') ?></h2>
            </div>
        </div>
        <p class="section__sub"><?= $t('services.sub') ?></p>

        <div class="services__pillars">

            <?php foreach (['p1', 'p2'] as $p): ?>
            <article class="pillar pillar--<?= $p ?>">
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

<!-- ─── STACK TECHNIQUE ──────────────────────────────────── -->
<section class="stack" id="stack">
    <span class="section__watermark" aria-hidden="true">02</span>
    <div class="stack__head stagger-group">
        <p class="eyebrow reveal"><?= $t('stack.eyebrow') ?></p>
        <h2 class="section__title reveal"><?= $tRaw('stack.title') ?></h2>
        <p class="section__sub reveal"><?= $tRaw('stack.lede') ?></p>
    </div>
    <ol class="stack__grid">
        <?php foreach ($stack as $i => $card): ?>
        <li class="stack__col reveal">
            <div class="stack__col-num" aria-hidden="true"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
            <div class="stack__col-head">
                <span class="stack__col-role"><?= $t($card['role_key']) ?></span>
            </div>
            <h3 class="stack__col-title"><?= $tRaw($card['title_key']) ?></h3>
            <p class="stack__col-sub"><?= $t($card['sub_key']) ?></p>
            <ul class="stack__techs">
                <?php foreach ($card['techs'] as $tech): ?>
                <li class="stack__tech">
                    <div class="stack__tech-name">
                        <span><?= htmlspecialchars($tech['name']) ?></span>
                        <span class="stack__tech-meta"><?= htmlspecialchars($tech['meta']) ?></span>
                    </div>
                    <div class="stack__tech-bar" style="--lvl: <?= (int) $tech['lvl'] ?>%" aria-hidden="true"></div>
                </li>
                <?php endforeach; ?>
            </ul>
            <p class="stack__col-foot"><?= $tRaw($card['foot_key']) ?></p>
        </li>
        <?php endforeach; ?>
    </ol>
</section>


<!-- ─── TRAVAUX (2 réalisations featured) ────────────────── -->
<section class="section home-projects" id="projects">
    <span class="section__watermark" aria-hidden="true">03</span>
    <div class="home-projects__inner">
        <header class="home-projects__header">
            <div>
                <p class="eyebrow"><?= $t('home.projects.eyebrow') ?></p>
                <h2 class="home-projects__title section__title">
                    <?= $t('home.projects.title') ?>
                    <em><?= $t('home.projects.title_em') ?></em>
                </h2>
                <p class="home-projects__lede"><?= $t('home.projects.lede') ?></p>
            </div>
        </header>
        <div class="home-projects__grid">
            <?php foreach ($projects as $i => $project): ?>
            <article class="home-project-card">
                <div class="frame-macbook">
                    <?= picture($project['image'], $t($project['title_key']), null, null, [
                        'loading'  => $i === 0 ? 'eager' : 'lazy',
                        'decoding' => $i === 0 ? 'sync' : 'async',
                    ]) ?>
                </div>
                <div class="home-project-card__eyebrow">
                    <span><?= $t('projects.kind.realisation') ?> · <?= htmlspecialchars($project['year']) ?></span>
                    <span><?= $t($project['type_key']) ?></span>
                </div>
                <h3 class="home-project-card__title"><?= $t($project['title_key']) ?></h3>
                <p class="home-project-card__pitch"><?= $t($project['pitch_key']) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="home-projects__footer">
            <a href="<?= $base ?>/projets" class="home-projects__see-all">
                <?= $t('home.projects.see_all') ?> →
            </a>
        </div>
    </div>
</section>

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

<!-- ─── À PROPOS ─────────────────────────────────────────── -->
<section class="section about" id="about">
    <span class="section__watermark" aria-hidden="true">05</span>
    <div class="section__inner">
        <div class="about__portrait" aria-hidden="true">
            <div class="about__portrait-photo">
                <?= picture('/assets/images/sonia2.webp', '', 220, 220, [
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                ]) ?>
            </div>
            <div class="about__portrait-nameplate">
                <span>Sonia Habibi</span>
                <span><?= $lang === 'fr' ? 'Dev Full-Stack · Vannes / Remote' : 'Full-Stack Dev · Vannes / Remote' ?></span>
            </div>
        </div>
        <div class="about__body">
            <div class="about__text">
                <p class="eyebrow"><?= $t('about.eyebrow') ?></p>
                <h2 class="section__title"><?= $tRaw('about.title') ?></h2>
                <p><?= $t('about.p1') ?></p>
                <p><?= $t('about.p2') ?></p>
                <blockquote class="about__pullquote"><?= $t('about.pullquote') ?></blockquote>
            </div>
            <div class="about__values">
                <p class="eyebrow"><?= $t('about.values.eyebrow') ?></p>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="about-value">
                    <strong class="about-value__title"><?= $t("about.values.{$i}.t") ?></strong>
                    <p class="about-value__desc"><?= $t("about.values.{$i}.d") ?></p>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<!-- ─── FAQ ─────────────────────────────────────────────── -->
<section class="section faq" id="faq">
    <div class="section__inner">
        <div class="section__head">
            <div>
                <p class="eyebrow"><?= $t('faq.eyebrow') ?></p>
                <h2 class="section__title"><?= $tRaw('faq.title') ?></h2>
            </div>
        </div>
        <div class="faq__list">
            <?php foreach ($faqItems as $idx => $i): ?>
            <details class="faq-item" <?= $idx === 0 ? 'open' : '' ?>>
                <summary class="faq-item__summary">
                    <span class="faq-item__q"><?= $t("faq.{$i}.q") ?></span>
                    <span class="faq-item__chev" aria-hidden="true">+</span>
                </summary>
                <p class="faq-item__a"><?= $t("faq.{$i}.a") ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Band — always-dark finale -->
<section class="cta-band">
    <p class="eyebrow"><?= $t('contact.eyebrow') ?></p>
    <h2 class="cta-band__title"><?= $t('cta.title') ?></h2>
    <p class="cta-band__sub"><?= $t('cta.sub') ?></p>
    <a href="mailto:<?= $t('cta.email') ?>" class="btn btn--dark btn--lg"><?= $t('cta.button') ?></a>
    <p class="cta-band__email">
        <a href="mailto:<?= $t('cta.email') ?>"><?= $t('cta.email') ?></a>
        · <?= $t('cta.replytime') ?>
    </p>
</section>
