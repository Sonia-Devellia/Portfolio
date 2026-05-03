<?php
/**
 * Vue — page d'accueil.
 *
 * @var callable(string): string                  $t         Helper de traduction
 * @var array<int, array<string, mixed>>          $projects  Projets featured affichés
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
$t ??= static fn(string $k): string => $k;
$projects ??= [];
$lang = $_SESSION['lang'] ?? 'fr';

function tagColor(string $tag): string {
    $tag = strtolower($tag);
    if (str_contains($tag, 'php'))    return 'tag--blue';
    if (str_contains($tag, 'python')) return 'tag--green';
    if (str_contains($tag, 'js') || str_contains($tag, 'javascript')) return 'tag--amber';
    if (str_contains($tag, 'ia') || str_contains($tag, 'llm') || str_contains($tag, 'ai') || str_contains($tag, 'claude') || str_contains($tag, 'openai')) return 'tag--purple';
    if (str_contains($tag, 'scss') || str_contains($tag, 'css')) return 'tag--coral';
    return 'tag--gray';
}
?>

<!-- ─── HERO ────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero__content">
        <p class="eyebrow"><?= $t('hero.eyebrow') ?></p>

        <?php if ($lang === 'fr'): ?>
        <h1 class="hero__title">Je construis des apps web<br>
        avec de l'<em>IA&nbsp;embarquée</em>.</h1>
        <?php else: ?>
        <h1 class="hero__title">I build web apps<br>
        with <em>embedded&nbsp;AI</em>.</h1>
        <?php endif; ?>

        <p class="hero__sub"><?= $t('hero.sub') ?></p>
        <div class="hero__actions">
            <a href="<?= $base ?>/projets" class="btn btn--dark"><?= $t('hero.cta_projects') ?></a>
            <a href="<?= $base ?>/contact" class="btn btn--outline"><?= $t('hero.cta_contact') ?></a>
        </div>
        <div class="hero__tags">
            <span class="tag tag--blue">PHP</span>
            <span class="tag tag--green">Python</span>
            <span class="tag tag--purple">LLM APIs</span>
        </div>
    </div>
    <div class="hero__photo">
        <img src="<?= $base ?>/assets/images/sonia.webp"
             alt="<?= $t('hero.img.alt') ?>"
             class="hero__img"
             width="480" height="560"
             loading="eager"
             fetchpriority="high"
             decoding="sync">
        <div class="hero__nameplate">
            <span class="hero__nameplate-name">Sonia Habibi</span>
            <span class="hero__nameplate-role"><?= $t('hero.nameplate.role') ?></span>
        </div>
    </div>
</section>

<!-- ─── FACTS STRIP ──────────────────────────────────────── -->
<div class="facts-strip" role="list">
    <div class="facts-strip__item" role="listitem">
        <span class="facts-strip__num">5+</span>
        <span class="facts-strip__lbl"><?= $t('home.stats.delivered') ?></span>
    </div>
    <div class="facts-strip__item" role="listitem">
        <span class="facts-strip__num">PHP · Py · JS</span>
        <span class="facts-strip__lbl"><?= $t('home.stats.stack') ?></span>
    </div>
    <div class="facts-strip__item" role="listitem">
        <span class="facts-strip__num">IA native</span>
        <span class="facts-strip__lbl"><?= $t('home.stats.ai') ?></span>
    </div>
    <div class="facts-strip__item" role="listitem">
        <span class="facts-strip__num">100%</span>
        <span class="facts-strip__lbl"><?= $t('home.stats.remote') ?></span>
    </div>
</div>

<!-- ─── CREDENTIAL HOOK ──────────────────────────────────── -->
<div class="credential-hook">
    <p><?= $t('hero.credential') ?></p>
</div>

<!-- ─── SERVICES ────────────────────────────────────────── -->
<section class="section services" id="services">
    <div class="section__head">
        <p class="eyebrow"><?= $t('services.eyebrow') ?></p>
        <h2 class="section__title"><?= $t('services.title') ?></h2>
    </div>
    <div class="services__grid">
        <div class="service-card">
            <span class="service-card__num">01</span>
            <h3 class="service-card__title"><?= $t('services.1.title') ?></h3>
            <p class="service-card__desc"><?= $t('services.1.desc') ?></p>
            <div class="service-card__tags">
                <span class="tag tag--blue">PHP</span>
                <span class="tag tag--amber">MySQL</span>
                <span class="tag tag--amber">JS</span>
            </div>
        </div>
        <div class="service-card">
            <span class="service-card__num">02</span>
            <h3 class="service-card__title"><?= $t('services.2.title') ?></h3>
            <p class="service-card__desc"><?= $t('services.2.desc') ?></p>
            <div class="service-card__tags">
                <span class="tag tag--purple">Claude API</span>
                <span class="tag tag--purple">OpenAI</span>
                <span class="tag tag--green">Python</span>
            </div>
        </div>
        <div class="service-card">
            <span class="service-card__num">03</span>
            <h3 class="service-card__title"><?= $t('services.3.title') ?></h3>
            <p class="service-card__desc"><?= $t('services.3.desc') ?></p>
            <div class="service-card__tags">
                <span class="tag tag--green">Python</span>
                <span class="tag tag--blue">APIs REST</span>
            </div>
        </div>
    </div>
</section>

<!-- ─── IA SCALE-UP ──────────────────────────────────────── -->
<section class="ai-scale" id="ai-scale">
    <div class="ai-scale__inner">
        <p class="eyebrow"><?= $t('ai.scale.eyebrow') ?></p>
        <div class="ai-scale__metrics" role="list">
            <div class="ai-scale__metric" role="listitem">
                <span class="ai-scale__num" data-target="3" data-suffix="×"><?= $t('ai.scale.m1.num') ?></span>
                <span class="ai-scale__lbl"><?= $t('ai.scale.m1.lbl') ?></span>
            </div>
            <div class="ai-scale__metric" role="listitem">
                <span class="ai-scale__num" data-target="24" data-suffix="/7"><?= $t('ai.scale.m2.num') ?></span>
                <span class="ai-scale__lbl"><?= $t('ai.scale.m2.lbl') ?></span>
            </div>
            <div class="ai-scale__metric" role="listitem">
                <span class="ai-scale__num" data-target="8" data-suffix="<?= $lang === 'fr' ? ' sem' : ' wks' ?>"><?= $t('ai.scale.m3.num') ?></span>
                <span class="ai-scale__lbl"><?= $t('ai.scale.m3.lbl') ?></span>
            </div>
        </div>
        <div class="ai-scale__body">
            <h2 class="ai-scale__assertion"><?= $t('ai.scale.title') ?></h2>
            <div class="ai-scale__text">
                <p><?= $t('ai.scale.body') ?></p>
                <a href="<?= $base ?>/contact" class="btn btn--outline btn--sm"><?= $t('hero.cta_contact') ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ─── PROJETS FEATURED ─────────────────────────────────── -->
<section class="section projects" id="projects">
    <div class="section__head">
        <div>
            <p class="eyebrow"><?= $t('projects.eyebrow') ?></p>
            <h2 class="section__title"><?= $t('projects.title') ?></h2>
        </div>
        <a href="<?= $base ?>/projets" class="btn btn--outline btn--sm"><?= $t('projects.see_all') ?></a>
    </div>

    <div class="projects__carousel" role="list">
        <?php foreach ($projects as $i => $project):
            $pLang  = $lang;
            $title  = htmlspecialchars($project['title_' . $pLang]);
            $tags   = \App\Models\Project::parseTags($project['tags']);
        ?>
        <article class="project-card <?= $project['is_wip'] ? 'project-card--wip' : '' ?>" data-theme="dark" role="listitem">
            <?php if ($project['is_wip']): ?>
                <div class="project-card__thumb project-card__thumb--empty">
                    <span class="project-card__wip-label">+ IA</span>
                </div>
            <?php elseif ($project['thumbnail']): ?>
                <div class="project-card__thumb">
                    <img src="<?= htmlspecialchars($project['thumbnail']) ?>"
                         alt="<?= $title ?>"
                         width="560" height="320"
                         loading="lazy"
                         decoding="async">
                </div>
            <?php else: ?>
                <div class="project-card__thumb project-card__thumb--placeholder">
                    <span><?= strtoupper(substr($title, 0, 2)) ?></span>
                </div>
            <?php endif; ?>

            <div class="project-card__body">
                <div class="project-card__tags">
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag <?= tagColor($tag) ?>"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
                <h3 class="project-card__title"><?= $title ?></h3>
                <div class="project-card__links">
                    <?php if ($project['is_wip']): ?>
                        <span class="project-card__link project-card__link--muted"><?= $t('projects.wip') ?></span>
                    <?php elseif ($project['github_url']): ?>
                        <a href="<?= htmlspecialchars($project['github_url']) ?>"
                           class="project-card__link"
                           target="_blank" rel="noopener"
                           aria-label="<?= $title ?> GitHub (<?= $t('contact.social.new_tab') ?>)">GitHub ↗</a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- ─── MOBILE FIRST ─────────────────────────────────────── -->
<section class="mobile-first" id="mobile-first">
    <div class="mobile-first__inner">
        <div class="mobile-first__content">
            <p class="eyebrow"><?= $t('mobile.first.eyebrow') ?></p>
            <h2 class="mobile-first__statement"><?= $t('mobile.first.title') ?></h2>
            <ul class="mobile-first__bullets">
                <li><?= $t('mobile.first.b1') ?></li>
                <li><?= $t('mobile.first.b2') ?></li>
                <li><?= $t('mobile.first.b3') ?></li>
            </ul>
        </div>
        <div class="mobile-first__device" aria-hidden="true">
            <div class="device">
                <div class="device__screen">
                    <div class="device__screen-header">
                        <div class="device__dots">
                            <span class="device__dot"></span>
                            <span class="device__dot"></span>
                            <span class="device__dot"></span>
                        </div>
                    </div>
                    <div class="device__line device__line--a"></div>
                    <div class="device__line device__line--b"></div>
                    <div class="device__line device__line--c"></div>
                    <div class="device__loadbar">
                        <div class="device__loadbar-fill"></div>
                    </div>
                    <div class="device__line device__line--d"></div>
                    <div class="device__line device__line--e"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── À PROPOS ─────────────────────────────────────────── -->
<section class="section about" id="about">
    <div class="about__text">
        <p class="eyebrow"><?= $t('about.eyebrow') ?></p>
        <h2 class="section__title"><?= $t('about.title') ?></h2>
        <p><?= $t('about.p1') ?></p>
        <p><?= $t('about.p2') ?></p>
    </div>
    <div class="about__timeline">
        <blockquote class="about__pullquote"><?= $t('about.pullquote') ?></blockquote>
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="timeline-item">
            <span class="timeline-item__year"><?= $t("about.tl.{$i}.year") ?></span>
            <div class="timeline-item__content">
                <span class="timeline-item__title"><?= $t("about.tl.{$i}.title") ?></span>
                <span class="timeline-item__sub"><?= $t("about.tl.{$i}.sub") ?></span>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</section>

<!-- ─── SECTION NAV (breadcrumb latéral) ─────────────────── -->
<nav class="section-nav" id="sectionNav" aria-label="<?= $lang === 'fr' ? 'Navigation sections' : 'Section navigation' ?>">
    <div class="section-nav__track">
        <button class="section-nav__dot" data-target="services" aria-label="Services"></button>
        <button class="section-nav__dot" data-target="projects" aria-label="<?= $t('nav.projects') ?>"></button>
        <button class="section-nav__dot" data-target="ai-scale" aria-label="IA"></button>
        <button class="section-nav__dot" data-target="mobile-first" aria-label="Mobile"></button>
        <button class="section-nav__dot" data-target="about" aria-label="<?= $t('nav.about') ?>"></button>
    </div>
</nav>

<!-- ─── CTA BAND ─────────────────────────────────────────── -->
<section class="cta-band">
    <p class="eyebrow"><?= $t('contact.eyebrow') ?></p>
    <h2 class="cta-band__title"><?= $t('cta.title') ?></h2>
    <p class="cta-band__sub"><?= $t('cta.sub') ?></p>
    <a href="<?= $base ?>/contact" class="btn btn--dark btn--lg"><?= $t('cta.button') ?></a>
</section>
