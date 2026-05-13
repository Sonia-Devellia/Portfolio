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

<!-- ─── SERVICES ────────────────────────────────────────── -->
<?php include __DIR__ . '/_services.php'; ?>

<!-- ─── STACK TECHNIQUE ──────────────────────────────────── -->
<section class="stack" id="stack">
    <div class="stack__bigNum" aria-hidden="true">02</div>
    <div class="stack__head stagger-group">
        <p class="eyebrow reveal"><?= $t('stack.eyebrow') ?></p>
        <h2 class="section__title reveal"><?= $tRaw('stack.title') ?></h2>
        <p class="section__sub reveal"><?= $tRaw('stack.lede') ?></p>
    </div>
    <ol class="stack__list">
        <?php foreach ($stack as $i => $card): ?>
        <li class="stack__row stack__row--<?= $card['side'] ?>">
            <?php if ($card['side'] === 'right'): ?>
            <div class="stack__dot reveal"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
            <?php endif; ?>
            <article class="stack__card reveal reveal--<?= $card['side'] ?>">
                <div class="card__head">
                    <span class="card__role"><?= $t($card['role_key']) ?></span>
                    <span class="card__count"><?= sprintf('%02d / %02d', $i + 1, count($stack)) ?></span>
                </div>
                <h3 class="card__title"><?= $tRaw($card['title_key']) ?></h3>
                <p class="card__sub"><?= $t($card['sub_key']) ?></p>
                <ul class="card__techs">
                    <?php foreach ($card['techs'] as $tech): ?>
                    <li class="card__tech">
                        <div class="card__tech-name">
                            <span><?= htmlspecialchars($tech['name']) ?></span>
                            <span><?= htmlspecialchars($tech['meta']) ?></span>
                        </div>
                        <div class="card__tech-bar" style="--lvl: <?= (int) $tech['lvl'] ?>%" aria-hidden="true"></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <p class="card__foot"><?= $tRaw($card['foot_key']) ?></p>
            </article>
            <?php if ($card['side'] === 'left'): ?>
            <div class="stack__dot reveal"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
            <?php endif; ?>
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
                    <img src="<?= $base . htmlspecialchars($project['image']) ?>"
                         alt="<?= $t($project['title_key']) ?>"
                         loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                         decoding="<?= $i === 0 ? 'sync' : 'async' ?>">
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

<?php include __DIR__ . '/_method.php'; ?>

<!-- ─── À PROPOS ─────────────────────────────────────────── -->
<section class="section about" id="about">
    <span class="section__watermark" aria-hidden="true">05</span>
    <div class="section__inner">
        <div class="about__portrait" aria-hidden="true">
            <div class="about__portrait-photo">
                <img src="<?= $base ?>/assets/images/sonia2.webp"
                     alt=""
                     width="220" height="220"
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
                <h2 class="section__title"><?= $tRaw('about.title') ?></h2>
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
