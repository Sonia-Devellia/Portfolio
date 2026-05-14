<?php
/**
 * Vue — Page géographique locale.
 *
 * @var callable(string): string $t
 * @var callable(string): string $tRaw
 * @var string  $city       Ex: "Paris"
 * @var string  $cityEn     Ex: "Paris"
 * @var string  $region     Ex: "Île-de-France"
 * @var string  $country    Ex: "France"
 * @var string  $slug       Ex: "paris"
 * @var bool    $isAbroad   true si le pays n'est pas la France (Luxembourg, Suisse...)
 * @var array   $breadcrumbSchema
 */
$base = base_url();
$lang = $_SESSION['lang'] ?? 'fr';
$isFr = $lang === 'fr';

$isAbroad         ??= false;
$breadcrumbSchema ??= [];
$context          ??= null;

$cityName = $isFr ? $city : $cityEn;

$ctxHeadline   = $context[$isFr ? 'headline_fr'   : 'headline_en']   ?? null;
$ctxEcosystem  = $context[$isFr ? 'ecosystem_fr'  : 'ecosystem_en']  ?? null;
$ctxReferences = $context[$isFr ? 'references_fr' : 'references_en'] ?? null;
$ctxSectors    = $context['sectors'] ?? [];
?>

<?php foreach ($extraSchemas as $schema): ?>
<script type="application/ld+json">
<?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>
<?php endforeach; ?>

<!-- ─── HERO ──────────────────────────────────────────────── -->
<section class="section geo-hero">
    <div class="section__inner">
        <!-- Breadcrumb visible -->
        <nav class="geo-breadcrumb" aria-label="<?= $isFr ? 'Fil d\'Ariane' : 'Breadcrumb' ?>">
            <a href="<?= $base ?>/"><?= $isFr ? 'Accueil' : 'Home' ?></a>
            <span aria-hidden="true"> / </span>
            <span><?= $isFr ? "Développeuse freelance {$cityName}" : "Freelance developer {$cityName}" ?></span>
        </nav>

        <p class="eyebrow">
            <?= $isFr
                ? strtoupper("DÉVELOPPEUSE FREELANCE · {$cityName}")
                : strtoupper("FREELANCE DEVELOPER · {$cityName}") ?>
        </p>
        <h1 class="section__title geo-hero__title">
            <?= $isFr
                ? "Développeuse freelance PHP, Python et IA — <em>{$cityName}</em>"
                : "Freelance PHP, Python & AI developer — <em>{$cityName}</em>" ?>
        </h1>
        <p class="geo-hero__sub">
            <?php if ($isAbroad): ?>
                <?= $isFr
                    ? "Basée à Vannes (Bretagne), je collabore 100 % en remote avec des clients en {$country}, notamment à {$city} et en {$region}. Facturation en euros, réponse sous 24h."
                    : "Based in Vannes (Brittany), I collaborate 100% remotely with clients in {$country}, including in {$cityEn} and the {$region}. Invoicing in euros, reply within 24h." ?>
            <?php else: ?>
                <?= $isFr
                    ? "Je travaille avec des startups et PME à {$city} et dans toute la {$region}, 100 % en remote. Réponse sous 24h, démarrage sous 2-4 semaines."
                    : "I work with startups and SMBs in {$cityEn} and throughout {$region}, 100% remote. Reply within 24h, start within 2-4 weeks." ?>
            <?php endif; ?>
        </p>
        <div class="geo-hero__actions">
            <a href="<?= $base ?>/contact" class="btn btn--dark"><?= $isFr ? 'Discuter de votre projet →' : 'Discuss your project →' ?></a>
            <a href="<?= $base ?>/projets" class="btn btn--outline"><?= $isFr ? 'Voir mes réalisations' : 'View my work' ?></a>
        </div>
        <div class="hero__tags">
            <span class="tag tag--blue">PHP 8.1</span>
            <span class="tag tag--green">Python</span>
            <span class="tag tag--amber">JavaScript</span>
            <span class="tag tag--purple">LLM / IA</span>
            <span class="tag tag--gray">Remote</span>
        </div>
    </div>
</section>

<!-- ─── ÉCOSYSTÈME LOCAL — contenu unique par ville (anti thin content) ─ -->
<?php if ($ctxHeadline || $ctxEcosystem): ?>
<section class="section geo-local">
    <div class="section__inner geo-local__inner">
        <p class="eyebrow">
            <?= $isFr ? "ÉCOSYSTÈME · {$cityName}" : "ECOSYSTEM · {$cityName}" ?>
        </p>
        <?php if ($ctxHeadline): ?>
        <h2 class="section__title geo-local__headline"><?= htmlspecialchars($ctxHeadline) ?></h2>
        <?php endif; ?>
        <?php if ($ctxEcosystem): ?>
        <p class="geo-local__body"><?= htmlspecialchars($ctxEcosystem) ?></p>
        <?php endif; ?>
        <?php if (!empty($ctxSectors)): ?>
        <div class="geo-local__sectors">
            <span class="geo-local__sectors-label"><?= $isFr ? 'Secteurs porteurs :' : 'Key sectors:' ?></span>
            <?php foreach ($ctxSectors as $sector): ?>
            <span class="tag tag--gray"><?= htmlspecialchars($sector) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($ctxReferences): ?>
        <aside class="geo-local__refs">
            <p class="geo-local__refs-label"><?= $isFr ? 'Réalisations dans cette zone' : 'Realisations in this area' ?></p>
            <p class="geo-local__refs-body"><?= htmlspecialchars($ctxReferences) ?></p>
            <a href="<?= $base ?>/projets" class="geo-local__refs-link">
                <?= $isFr ? 'Voir le détail des projets →' : 'See project details →' ?>
            </a>
        </aside>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- ─── SERVICES ─────────────────────────────────────────── -->
<section class="section geo-services">
    <div class="section__inner">
        <p class="eyebrow"><?= $isFr ? 'CE QUE JE FAIS' : 'WHAT I DO' ?></p>
        <h2 class="section__title">
            <?= $isFr
                ? "Services de développement web à {$cityName}"
                : "Web development services in {$cityName}" ?>
        </h2>
        <p class="geo-services__intro">
            <?= $isFr
                ? "Développement d'applications web sur mesure, MVPs, logiciels métier et intégration d'IA pour les entreprises de {$region} et au-delà. Tout se passe en remote — appels vidéo, livrables partagés, code auditable."
                : "Custom web application development, MVPs, business software and AI integration for companies in {$region} and beyond. Everything happens remotely — video calls, shared deliverables, auditable code." ?>
        </p>
        <div class="geo-services__grid">
            <?php
            $services = $isFr ? [
                ['tag--blue',   'PHP 8.1 MVC',       'Applications web sur mesure, back-offices, APIs REST. Architecture propre, sans dépendance CMS.'],
                ['tag--green',  'Python',             'Automatisation, data pipelines, scrapers intelligents, connexion d\'APIs tierces.'],
                ['tag--purple', 'Intégration IA',     'Intégration Claude API, OpenAI. Classifieurs, extraction documentaire, agents. Avec garde-fous et fallback.'],
                ['tag--amber',  'JavaScript',         'Front-end vanilla ou React. Lighthouse ≥ 90, accessibilité, mobile-first.'],
                ['tag--coral',  'MVP web',            'De l\'idée au livrable en 6-10 semaines. Scope cadré, spec validée, code en prod.'],
                ['tag--gray',   'Reprise de projets', 'Audit en 5 jours, plan priorisé, refonte ciblée. Je reprends le code des autres.'],
            ] : [
                ['tag--blue',   'PHP 8.1 MVC',       'Custom web applications, back-offices, REST APIs. Clean architecture, no CMS dependency.'],
                ['tag--green',  'Python',             'Automation, data pipelines, intelligent scrapers, third-party API connectors.'],
                ['tag--purple', 'AI integration',     'Claude API, OpenAI integration. Classifiers, document extraction, agents. With guardrails and fallback.'],
                ['tag--amber',  'JavaScript',         'Vanilla or React front end. Lighthouse ≥ 90, accessibility, mobile-first.'],
                ['tag--coral',  'Web MVP',            'From idea to live product in 6-10 weeks. Scoped brief, validated spec, code in production.'],
                ['tag--gray',   'Project recovery',   '5-day audit, prioritised plan, targeted refactor. I take over other people\'s code.'],
            ];
            foreach ($services as [$tagClass, $name, $desc]): ?>
            <article class="geo-service-card">
                <span class="tag <?= $tagClass ?>"><?= htmlspecialchars($name) ?></span>
                <p><?= htmlspecialchars($desc) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── POURQUOI REMOTE ───────────────────────────────────── -->
<section class="section geo-remote">
    <div class="section__inner geo-remote__inner">
        <div class="geo-remote__text">
            <p class="eyebrow"><?= $isFr ? 'REMOTE & EFFICACE' : 'REMOTE & EFFICIENT' ?></p>
            <h2 class="section__title">
                <?= $isFr
                    ? "Pourquoi choisir une développeuse freelance remote pour votre projet à {$cityName} ?"
                    : "Why choose a remote freelance developer for your project in {$cityName}?" ?>
            </h2>
            <ul class="geo-remote__list">
                <li>
                    <strong><?= $isFr ? 'Zéro coût d\'agence locale' : 'Zero local agency overhead' ?></strong>
                    <span><?= $isFr ? 'Pas de surcoût géographique — vous payez pour le code livré, pas pour les locaux.' : 'No geographic premium — you pay for code delivered, not for office space.' ?></span>
                </li>
                <li>
                    <strong><?= $isFr ? 'Réactivité garantie' : 'Guaranteed responsiveness' ?></strong>
                    <span><?= $isFr ? 'Réponse sous 24h, même timezone Europe. Appels vidéo à votre convenance.' : 'Reply within 24h, same European timezone. Video calls at your convenience.' ?></span>
                </li>
                <li>
                    <strong><?= $isFr ? 'Code auditable à tout moment' : 'Code auditable at any time' ?></strong>
                    <span><?= $isFr ? 'Git discipliné, pull requests commentées, documentation livrée avec le code.' : 'Disciplined git, commented pull requests, documentation delivered with the code.' ?></span>
                </li>
                <li>
                    <strong><?= $isFr ? 'Interlocuteur unique' : 'Single point of contact' ?></strong>
                    <span><?= $isFr ? 'Pas de chef de projet entre vous et le développement — la décision technique est directe.' : 'No project manager between you and development — technical decision is direct.' ?></span>
                </li>
            </ul>
        </div>
        <aside class="geo-remote__stats">
            <div class="geo-stat">
                <span class="geo-stat__num">100%</span>
                <span class="geo-stat__label"><?= $isFr ? 'Remote' : 'Remote' ?></span>
            </div>
            <div class="geo-stat">
                <span class="geo-stat__num">24h</span>
                <span class="geo-stat__label"><?= $isFr ? 'Réponse garantie' : 'Guaranteed reply' ?></span>
            </div>
            <div class="geo-stat">
                <span class="geo-stat__num">6–10</span>
                <span class="geo-stat__label"><?= $isFr ? 'semaines pour un MVP' : 'weeks for an MVP' ?></span>
            </div>
            <div class="geo-stat">
                <span class="geo-stat__num">600–800€</span>
                <span class="geo-stat__label"><?= $isFr ? '/ jour HT' : '/ day excl. VAT' ?></span>
            </div>
        </aside>
    </div>
</section>

<!-- ─── FAQ LOCALE ────────────────────────────────────────── -->
<section class="section faq" id="faq">
    <div class="section__inner">
        <div class="section__head">
            <div>
                <p class="eyebrow"><?= $isFr ? 'FAQ' : 'FAQ' ?></p>
                <h2 class="section__title">
                    <?= $isFr
                        ? "Développeuse freelance à {$cityName} — questions fréquentes"
                        : "Freelance developer in {$cityName} — frequently asked questions" ?>
                </h2>
            </div>
        </div>
        <div class="faq__list">
            <?php
            $faqs = $isFr ? [
                ["Travaillez-vous avec des clients à {$city} ?",
                 "Oui, 100 % en remote. Je collabore avec des startups et PME à {$city}, dans toute la {$region} et à l'international. Réunions vidéo, Notion, Slack — la distance n'est pas un frein."],
                ["Quel est votre TJM pour un projet à {$city} ?",
                 "600 à 800 €/jour HT selon la complexité du projet et sa durée. Forfait possible à partir de 2 semaines de travail (~6 000 € HT minimum). Premier appel gratuit pour cadrer."],
                ["Avez-vous des références de clients en {$region} ?",
                 "Mes références sont confidentielles sauf accord client. Je peux partager des projets similaires par secteur (agences, SaaS, e-commerce, logiciels métier) sur demande lors du premier échange."],
                ["Faut-il se voir en présentiel pour travailler ensemble ?",
                 "Non. Mes projets se déroulent intégralement en remote — France et Europe. Je n'exige pas de déplacement. Si vous souhaitez un kick-off en présentiel, c'est possible à la discrétion et aux frais du client."],
                ["Quelle est la différence entre vous et une agence web locale à {$city} ?",
                 "Un freelance senior = interlocuteur unique, décision technique directe, TJM transparent sans surcoût commercial. Une agence = équipe plus large, mais coordination supplémentaire, marges internes et délais potentiellement plus longs. À vous de peser selon votre besoin."],
            ] : [
                ["Do you work with clients in {$cityEn}?",
                 "Yes, 100% remotely. I collaborate with startups and SMBs in {$cityEn}, throughout {$region} and internationally. Video meetings, Notion, Slack — distance is not a barrier."],
                ["What is your day rate for a project in {$cityEn}?",
                 "€600 to €800/day excl. VAT depending on project complexity and duration. Fixed-price quote available from 2 weeks of work (~€6,000 minimum). Free initial call to scope the project."],
                ["Do you have client references in {$region}?",
                 "My references are confidential unless the client agrees. I can share similar projects by sector (agencies, SaaS, e-commerce, business software) on request during the first call."],
                ["Is in-person meeting required to work together?",
                 "No. My projects are fully remote — France and Europe. I do not require travel. If you want an in-person kick-off, it is possible at the client's discretion and expense."],
                ["What is the difference between you and a local web agency in {$cityEn}?",
                 "A senior freelance = single point of contact, direct technical decision, transparent day rate without commercial markup. An agency = larger team, but additional coordination, internal margins and potentially longer timelines. The right choice depends on your needs."],
            ];
            foreach ($faqs as $idx => $faq): ?>
            <details class="faq-item" <?= $idx === 0 ? 'open' : '' ?>>
                <summary class="faq-item__summary">
                    <span class="faq-item__q"><?= htmlspecialchars($faq[0]) ?></span>
                    <span class="faq-item__chev" aria-hidden="true">+</span>
                </summary>
                <p class="faq-item__a"><?= htmlspecialchars($faq[1]) ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── CTA BAND ──────────────────────────────────────────── -->
<section class="cta-band">
    <p class="eyebrow"><?= $isFr ? 'TRAVAILLONS ENSEMBLE' : 'LET\'S WORK TOGETHER' ?></p>
    <h2 class="cta-band__title">
        <?= $isFr
            ? "Votre projet à {$cityName}, développé en remote."
            : "Your project in {$cityName}, developed remotely." ?>
    </h2>
    <p class="cta-band__sub">
        <?= $isFr
            ? 'Premier échange de 30 min pour cadrer le besoin et estimer la faisabilité. Réponse sous 24h.'
            : '30-min initial call to frame the need and assess feasibility. Reply within 24h.' ?>
    </p>
    <a href="<?= $base ?>/contact" class="btn btn--dark btn--lg">
        <?= $isFr ? 'Écrire à Sonia →' : 'Write to Sonia →' ?>
    </a>
    <p class="cta-band__email">
        <a href="mailto:contact@sonia-habibi.dev">contact@sonia-habibi.dev</a>
        · <?= $isFr ? 'Réponse sous 24h, jours ouvrés.' : 'Reply within 24h, working days.' ?>
    </p>
</section>
