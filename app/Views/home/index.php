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

$methodStages = ['1a', '1b', '2a', '2b'];
$faqItems = ['1', '2', '3', '4', '5'];
?>

<!-- ─── HERO ────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero__content">
        <p class="hero__avail" aria-hidden="true">
            <span></span><?= $t('nav.available') ?>
        </p>
        <p class="eyebrow"><?= $t('hero.eyebrow') ?></p>

        <h1 class="hero__title"><?= $t('hero.title') ?></h1>

        <p class="hero__sub" id="heroSub"><?= $t('hero.sub') ?></p>
        <div class="hero__actions">
            <a href="<?= $base ?>/projets" class="btn btn--dark"><?= $t('hero.cta_projects') ?></a>
            <a href="<?= $base ?>/contact" class="btn btn--outline"><?= $t('hero.cta_contact') ?></a>
            <a href="<?= $base ?>/case-studies/triage-support" class="btn btn--ghost"><?= $t('hero.cta_case') ?></a>
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
<?php include __DIR__ . '/_services.php'; ?>

<!-- ─── TICKER ───────────────────────────────────────────── -->
<div class="ticker" aria-hidden="true">
    <div class="ticker__track">
        <span class="ticker__content">PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; </span>
        <span class="ticker__content">PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; PHP &nbsp;·&nbsp; Python &nbsp;·&nbsp; JavaScript &nbsp;·&nbsp; LLM &nbsp;·&nbsp; Remote &nbsp;·&nbsp; Disponible &nbsp;·&nbsp; Freelance &nbsp;·&nbsp; Full-Stack &nbsp;·&nbsp; Code en prod &nbsp;·&nbsp; </span>
    </div>
</div>

<!-- ─── PROJETS FEATURED — Mac mockup showcase ─────────────── -->
<section class="section projects" id="projects">
    <span class="section__watermark" aria-hidden="true">02</span>
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

<!-- ─── MÉTHODE DEV + IA ───────────────────────────────── -->
<section class="section method" id="method">
    <span class="section__watermark" aria-hidden="true">03</span>
    <div class="section__inner">
        <div class="method__intro">
            <p class="eyebrow"><?= $t('method.eyebrow') ?></p>
            <h2 class="section__title method__title"><?= $t('method.title') ?></h2>
            <p class="method__sub"><?= $t('method.sub') ?></p>
        </div>

        <div class="method__stages">
            <?php foreach ($methodStages as $k): ?>
            <article class="method-stage">
                <span class="method-stage__num"><?= $t("method.{$k}.num") ?></span>
                <div class="method-stage__content">
                    <h3 class="method-stage__title"><?= $t("method.{$k}.title") ?></h3>
                    <p class="method-stage__body"><?= $t("method.{$k}.body") ?></p>
                    <p class="method-stage__deliv">
                        <strong><?= $t('method.deliv.label') ?></strong>
                        <?= $t("method.{$k}.deliv") ?>
                    </p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <aside class="method__crit">
            <p class="method__crit-lbl"><?= $t('method.crit.lbl') ?></p>
            <p class="method__crit-body"><?= $t('method.crit.body') ?></p>
        </aside>
    </div>
</section>

<!-- ─── À PROPOS ─────────────────────────────────────────── -->
<section class="section about" id="about">
    <span class="section__watermark" aria-hidden="true">04</span>
    <div class="section__inner">
        <div class="about__portrait" aria-hidden="true">
            <div class="about__portrait-photo">
                <img src="<?= $base ?>/assets/images/sonia.webp"
                     alt=""
                     width="220" height="275"
                     loading="lazy"
                     decoding="async">
            </div>
            <div class="about__portrait-nameplate">
                <span>Sonia Habibi</span>
                <span><?= $lang === 'fr' ? 'Dev Full-Stack · Vannes / Remote' : 'Full-Stack Dev · Vannes / Remote' ?></span>
            </div>
        </div>
        <div class="about__body">
            <div class="about__text">
                <p class="eyebrow"><?= $t('about.eyebrow') ?></p>
                <h2 class="section__title"><?= $t('about.title') ?></h2>
                <p><?= $t('about.p1') ?></p>
                <p><?= $t('about.p2') ?></p>
                <blockquote class="about__pullquote"><?= $t('about.pullquote') ?></blockquote>
                <a href="#method" class="btn btn--outline btn--sm"><?= $t('about.cta') ?></a>
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
                <h2 class="section__title"><?= $t('faq.title') ?></h2>
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
