<?php
// Tags couleurs selon technologie
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
        <h1 class="hero__title"><?= $t('hero.title') ?></h1>
        <p class="hero__sub"><?= $t('hero.sub') ?></p>
        <div class="hero__actions">
            <a href="/projets" class="btn btn--dark"><?= $t('hero.cta_projects') ?></a>
            <a href="/contact" class="btn btn--outline"><?= $t('hero.cta_contact') ?></a>
        </div>
        <div class="hero__tags">
            <span class="tag tag--blue">PHP MVC</span>
            <span class="tag tag--green">Python</span>
            <span class="tag tag--amber">JavaScript</span>
            <span class="tag tag--purple">LLM APIs</span>
            <span class="tag tag--amber">React</span>
            <span class="tag tag--gray">MySQL</span>
            <span class="tag tag--gray">Git</span>
        </div>
    </div>
    <div class="hero__photo">
        <img src="/assets/images/sonia.webp"
             alt="Sonia Habibi — Développeuse Full-Stack"
             class="hero__img"
             width="480" height="560"
             loading="eager">
        <div class="hero__nameplate">
            <span class="hero__nameplate-name">Sonia Habibi</span>
            <span class="hero__nameplate-role">Dev Full-Stack · Vannes / Remote</span>
        </div>
    </div>
</section>

<!-- ─── STATS BAR ───────────────────────────────────────── -->
<div class="stats-bar">
    <div class="stats-bar__item">
        <span class="stats-bar__num">5+</span>
        <span class="stats-bar__lbl"><?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'Projets livrés' : 'Projects delivered' ?></span>
    </div>
    <div class="stats-bar__item">
        <span class="stats-bar__num">PHP · Py · JS</span>
        <span class="stats-bar__lbl"><?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'Stack principale' : 'Core stack' ?></span>
    </div>
    <div class="stats-bar__item">
        <span class="stats-bar__num">IA</span>
        <span class="stats-bar__lbl"><?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'Intégration LLM native' : 'Native LLM integration' ?></span>
    </div>
    <div class="stats-bar__item">
        <span class="stats-bar__num">100%</span>
        <span class="stats-bar__lbl">Remote</span>
    </div>
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

<!-- ─── PROJETS FEATURED ─────────────────────────────────── -->
<section class="section projects" id="projects">
    <div class="section__head">
        <div>
            <p class="eyebrow"><?= $t('projects.eyebrow') ?></p>
            <h2 class="section__title"><?= $t('projects.title') ?></h2>
        </div>
        <a href="/projets" class="btn btn--outline btn--sm"><?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'Voir tous →' : 'View all →' ?></a>
    </div>

    <div class="projects__grid">
        <?php foreach ($projects as $i => $project):
            $lang     = $_SESSION['lang'] ?? 'fr';
            $title    = htmlspecialchars($project['title_' . $lang]);
            $desc     = htmlspecialchars($project['desc_' . $lang]);
            $tags     = \App\Models\Project::parseTags($project['tags']);
            $isWide   = $i === 1 || $i === 2; // 2e et 3e projet en large
        ?>
        <article class="project-card <?= $isWide ? 'project-card--wide' : '' ?> <?= $project['is_wip'] ? 'project-card--wip' : '' ?>">
            <?php if ($project['is_wip']): ?>
                <div class="project-card__thumb project-card__thumb--empty">
                    <span class="project-card__wip-label">+ IA</span>
                </div>
            <?php elseif ($project['thumbnail']): ?>
                <div class="project-card__thumb">
                    <img src="<?= htmlspecialchars($project['thumbnail']) ?>"
                         alt="<?= $title ?>"
                         loading="lazy">
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
                <p class="project-card__desc"><?= $desc ?></p>
                <div class="project-card__links">
                    <?php if ($project['is_wip']): ?>
                        <span class="project-card__link project-card__link--muted"><?= $t('projects.wip') ?></span>
                    <?php else: ?>
                        <a href="/projets/<?= htmlspecialchars($project['slug']) ?>" class="project-card__link"><?= $t('projects.see') ?></a>
                        <?php if ($project['github_url']): ?>
                            <a href="<?= htmlspecialchars($project['github_url']) ?>" class="project-card__link" target="_blank" rel="noopener"><?= $t('projects.github') ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
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
        <?php
        $timeline = ($_SESSION['lang'] ?? 'fr') === 'fr' ? [
            ['year' => '2026', 'title' => 'Certification RNCP 37273 Full-Stack', 'sub' => 'GRETA Vannes'],
            ['year' => '2025', 'title' => 'Stage Kotcha — startup running & IA',  'sub' => 'IA centrale dans le produit'],
            ['year' => '2024', 'title' => 'Formation autodidacte structurée',      'sub' => 'PHP · Python · JS · MVC'],
            ['year' => 'Avant','title' => 'Cheffe d\'entreprise · Designer paysagiste', 'sub' => 'Culture produit, gestion client'],
        ] : [
            ['year' => '2026', 'title' => 'RNCP 37273 Full-Stack Certification',  'sub' => 'GRETA Vannes'],
            ['year' => '2025', 'title' => 'Internship Kotcha — running & AI app', 'sub' => 'AI at the core of the product'],
            ['year' => '2024', 'title' => 'Structured self-taught training',       'sub' => 'PHP · Python · JS · MVC'],
            ['year' => 'Before','title' => 'Business owner · Landscape designer', 'sub' => 'Product culture, client management'],
        ];
        foreach ($timeline as $item): ?>
        <div class="timeline-item">
            <span class="timeline-item__year"><?= $item['year'] ?></span>
            <div class="timeline-item__content">
                <span class="timeline-item__title"><?= htmlspecialchars($item['title']) ?></span>
                <span class="timeline-item__sub"><?= htmlspecialchars($item['sub']) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ─── CTA BAND ─────────────────────────────────────────── -->
<section class="cta-band">
    <div class="cta-band__text">
        <h2><?= $t('cta.title') ?></h2>
        <p><?= $t('cta.sub') ?></p>
    </div>
    <div class="cta-band__actions">
        <a href="https://www.linkedin.com" target="_blank" rel="noopener" class="btn btn--outline">LinkedIn</a>
        <a href="https://github.com/sonia-habibi" target="_blank" rel="noopener" class="btn btn--outline">GitHub</a>
        <a href="https://www.malt.fr" target="_blank" rel="noopener" class="btn btn--outline">Malt</a>
        <a href="/contact" class="btn btn--dark"><?= $t('cta.button') ?></a>
    </div>
</section>
