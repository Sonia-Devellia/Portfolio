<?php
/**
 * Vue — page d'accueil.
 *
 * @var callable(string): string                  $t
 * @var array<int, array<string, mixed>>          $projects
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

$aiTasks = $lang === 'fr'
    ? ['Analyse des données clients', 'Génération de rapport', 'Tri et classification']
    : ['Client data analysis', 'Report generation', 'Sort & classify'];
?>

<!-- ─── HERO ────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero__content">
        <p class="eyebrow"><?= $t('hero.eyebrow') ?></p>

        <?php if ($lang === 'fr'): ?>
        <h1 class="hero__title">Ingénieure full-stack.<br>
        <em>Vision produit.</em> Code en prod.</h1>
        <?php else: ?>
        <h1 class="hero__title">Full-stack engineer.<br>
        <em>Product mind.</em> Shipped code.</h1>
        <?php endif; ?>

        <p class="hero__sub" id="heroSub"><?= $t('hero.sub') ?></p>
        <div class="hero__actions">
            <a href="<?= $base ?>/projets" class="btn btn--dark"><?= $t('hero.cta_projects') ?></a>
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
    <div class="scroll-indicator" id="scrollIndicator" aria-hidden="true">
        <div class="scroll-indicator__line"></div>
    </div>
</section>

<!-- ─── SERVICES ────────────────────────────────────────── -->
<section class="section services" id="services">
    <span class="section__watermark" aria-hidden="true">01</span>
    <div class="section__inner">
        <div class="section__head">
            <p class="eyebrow"><?= $t('services.eyebrow') ?></p>
            <h2 class="section__title"><?= $t('services.title') ?></h2>
        </div>
        <div class="services__grid">
            <div class="service-card">
                <span class="service-card__num" data-num="1">01</span>
                <h3 class="service-card__title"><?= $t('services.1.title') ?></h3>
                <p class="service-card__desc"><?= $t('services.1.desc') ?></p>
                <div class="service-card__tags">
                    <span class="tag tag--blue">PHP</span>
                    <span class="tag tag--amber">MySQL</span>
                    <span class="tag tag--amber">JS</span>
                </div>
            </div>
            <div class="service-card">
                <span class="service-card__num" data-num="2">02</span>
                <h3 class="service-card__title"><?= $t('services.2.title') ?></h3>
                <p class="service-card__desc"><?= $t('services.2.desc') ?></p>
                <div class="service-card__tags">
                    <span class="tag tag--purple">Claude API</span>
                    <span class="tag tag--purple">OpenAI</span>
                    <span class="tag tag--green">Python</span>
                </div>
            </div>
            <div class="service-card">
                <span class="service-card__num" data-num="3">03</span>
                <h3 class="service-card__title"><?= $t('services.3.title') ?></h3>
                <p class="service-card__desc"><?= $t('services.3.desc') ?></p>
                <div class="service-card__tags">
                    <span class="tag tag--green">Python</span>
                    <span class="tag tag--blue">APIs REST</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── TICKER ───────────────────────────────────────────── -->
<div class="ticker" aria-hidden="true">
    <div class="ticker__track">
        <span class="ticker__content">PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; </span>
        <span class="ticker__content">PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; </span>
    </div>
</div>

<!-- ─── PROJETS FEATURED — Mac mockup showcase ─────────────── -->
<section class="section projects" id="projects">
  <div class="projects__header">
    <div>
      <p class="eyebrow"><?= $t('projects.eyebrow') ?></p>
      <h2 class="section__title"><?= $t('projects.title') ?></h2>
    </div>
  </div>
  <div class="projects__showcase">
    <!-- Left: Mac mockup -->
    <div class="mac-mockup">
      <div class="mac-mockup__bar">
        <span class="mac-dot mac-dot--red"></span>
        <span class="mac-dot mac-dot--yellow"></span>
        <span class="mac-dot mac-dot--green"></span>
      </div>
      <div class="mac-mockup__screen">
        <?php foreach ($projects as $i => $p):
          $pLang = $_SESSION['lang'] ?? 'fr'; ?>
        <div class="mac-slide <?= $i === 0 ? 'mac-slide--active' : '' ?>"
             data-index="<?= $i ?>">
          <?php if ($p['thumbnail']): ?>
            <img src="<?= htmlspecialchars($p['thumbnail']) ?>"
                 alt="<?= htmlspecialchars($p['title_' . $pLang]) ?>"
                 loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
          <?php else: ?>
            <div class="mac-slide__placeholder">
              <span><?= $p['is_wip'] ? '+ IA' : strtoupper(substr($p['title_' . $pLang], 0, 2)) ?></span>
            </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Right: project details -->
    <div class="projects__details">
      <?php foreach ($projects as $i => $p):
        $pLang = $_SESSION['lang'] ?? 'fr';
        $tags  = \App\Models\Project::parseTags($p['tags']); ?>
      <div class="project-detail <?= $i === 0 ? 'project-detail--active' : '' ?>"
           data-index="<?= $i ?>">
        <p class="project-detail__num">0<?= $i + 1 ?></p>
        <h3 class="project-detail__title">
          <?= htmlspecialchars($p['title_' . $pLang]) ?>
        </h3>
        <div class="project-detail__tags">
          <?php foreach ($tags as $tag): ?>
            <span class="tag tag--mono"><?= htmlspecialchars($tag) ?></span>
          <?php endforeach; ?>
        </div>
        <p class="project-detail__desc">
          <?= htmlspecialchars(mb_substr(strip_tags($p['desc_' . $pLang] ?? ''), 0, 200)) ?>
        </p>
        <?php if ($p['is_wip']): ?>
          <span class="project-detail__wip"><?= $t('projects.wip') ?></span>
        <?php elseif ($p['github_url']): ?>
          <a href="<?= htmlspecialchars($p['github_url']) ?>"
             class="btn btn--outline btn--sm"
             target="_blank" rel="noopener">GitHub →</a>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── À PROPOS ─────────────────────────────────────────── -->
<section class="section about" id="about">
    <span class="section__watermark" aria-hidden="true">03</span>
    <div class="section__inner">
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
