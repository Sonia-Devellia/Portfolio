<?php
/**
 * Vue — Page Tarifs & Modes de collaboration.
 *
 * @var callable(string): string $t
 * @var callable(string): string $tRaw
 * @var array                    $extraSchemas
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
$lang = $_SESSION['lang'] ?? 'fr';
$t  ??= static fn(string $k): string => $k;
$extraSchemas ??= [];

$isFr = $lang === 'fr';
?>

<!-- ─── HERO TARIFS ──────────────────────────────────────── -->
<section class="section tarifs-hero">
    <div class="section__inner">
        <p class="eyebrow"><?= $isFr ? 'COLLABORATION' : 'WORK WITH ME' ?></p>
        <h1 class="section__title tarifs-hero__title">
            <?= $isFr
                ? 'Quatre façons de <em>travailler ensemble</em>.'
                : 'Four ways to <em>work together</em>.' ?>
        </h1>
        <p class="tarifs-hero__lede">
            <?= $isFr
                ? 'Selon votre structure juridique, votre horizon de collaboration et vos besoins en intégration d\'équipe.'
                : 'Depending on your legal structure, collaboration horizon and team integration needs.' ?>
        </p>
    </div>
</section>

<!-- ─── GRILLE 3 OPTIONS ─────────────────────────────────── -->
<section class="section tarifs-grid-section">
    <div class="section__inner">
        <div class="tarifs-grid">

            <!-- ── OPTION 1 : FREELANCE ── -->
            <article class="tarif-card tarif-card--featured" id="freelance">
                <div class="tarif-card__badge">
                    <span class="tag tag--blue"><?= $isFr ? 'Disponible maintenant' : 'Available now' ?></span>
                </div>
                <p class="tarif-card__type"><?= $isFr ? 'Prestation freelance' : 'Freelance contract' ?></p>
                <div class="tarif-card__price">
                    <span class="tarif-card__amount">600–800 €</span>
                    <span class="tarif-card__unit"><?= $isFr ? '/ jour HT' : '/ day excl. VAT' ?></span>
                </div>
                <p class="tarif-card__forfait">
                    <?= $isFr ? 'Forfait à partir de 6 000 € HT · min. 2 semaines' : 'Fixed quote from €6,000 · min. 2 weeks' ?>
                </p>
                <ul class="tarif-card__list">
                    <li><?= $isFr ? 'Pas d\'engagement long terme' : 'No long-term commitment' ?></li>
                    <li><?= $isFr ? 'Code source livré — propriété du client' : 'Source code delivered — client ownership' ?></li>
                    <li><?= $isFr ? 'Facturation à la semaine ou au forfait' : 'Weekly or fixed-price invoicing' ?></li>
                    <li><?= $isFr ? 'Réponse sous 48h, démarrage sous 2-4 semaines' : 'Reply within 48h, start within 2-4 weeks' ?></li>
                    <li><?= $isFr ? 'NDA disponible, conditions standards' : 'NDA available, standard terms' ?></li>
                </ul>
                <div class="tarif-card__ideal">
                    <strong><?= $isFr ? 'Idéal pour :' : 'Ideal for:' ?></strong>
                    <?= $isFr
                        ? 'MVP à lancer, refonte ciblée, renfort ponctuel, audit + remise en état.'
                        : 'MVP launch, targeted rebuild, one-off reinforcement, audit + recovery.' ?>
                </div>
                <a href="<?= $base ?>/contact" class="btn btn--dark btn--full">
                    <?= $isFr ? 'Discuter d\'un projet →' : 'Discuss a project →' ?>
                </a>
            </article>

            <!-- ── OPTION 2 : CDI REMOTE ── -->
            <article class="tarif-card" id="cdi-remote">
                <p class="tarif-card__type"><?= $isFr ? 'CDI remote — temps plein' : 'Full-time remote CDI' ?></p>
                <div class="tarif-card__price">
                    <span class="tarif-card__amount"><?= $isFr ? 'Sur demande' : 'On request' ?></span>
                    <span class="tarif-card__unit"><?= $isFr ? '35h / semaine' : '35h / week' ?></span>
                </div>
                <p class="tarif-card__forfait">
                    <?= $isFr ? 'Remote 100 % · Pas de déménagement requis' : '100% remote · No relocation required' ?>
                </p>
                <ul class="tarif-card__list">
                    <li><?= $isFr ? 'Intégration complète dans votre équipe produit' : 'Full integration into your product team' ?></li>
                    <li><?= $isFr ? 'Télétravail 100 % — France, Suisse, EU' : '100% remote — France, Switzerland, EU' ?></li>
                    <li><?= $isFr ? 'Disponibilité dans votre stack et vos outils' : 'Availability within your stack and tooling' ?></li>
                    <li><?= $isFr ? 'Prétentions communiquées après premier échange' : 'Salary expectations shared after initial call' ?></li>
                    <li><?= $isFr ? 'Préavis légal à anticiper (1-3 mois)' : 'Legal notice period to plan for (1-3 months)' ?></li>
                </ul>
                <div class="tarif-card__ideal">
                    <strong><?= $isFr ? 'Idéal pour :' : 'Ideal for:' ?></strong>
                    <?= $isFr
                        ? 'Startups Series A/B, scale-ups, PME tech cherchant à internaliser un profil senior full-stack.'
                        : 'Series A/B startups, scale-ups, tech SMBs looking to internalise a senior full-stack profile.' ?>
                </div>
                <a href="<?= $base ?>/contact" class="btn btn--outline btn--full">
                    <?= $isFr ? 'En savoir plus →' : 'Learn more →' ?>
                </a>
            </article>

            <!-- ── OPTION 4 : FORMATION IA ── -->
            <article class="tarif-card" id="formation-ia">
                <div class="tarif-card__badge">
                    <span class="tag tag--amber"><?= $isFr ? 'Présentiel & distanciel' : 'On-site & remote' ?></span>
                </div>
                <p class="tarif-card__type"><?= $isFr ? 'Formation IA en entreprise' : 'Corporate AI Training' ?></p>
                <div class="tarif-card__price">
                    <span class="tarif-card__amount"><?= $isFr ? 'Sur devis' : 'On quote' ?></span>
                    <span class="tarif-card__unit"><?= $isFr ? '/ forfait' : '/ package' ?></span>
                </div>
                <p class="tarif-card__forfait">
                    <?= $isFr ? 'Demi-journée, journée ou programme multi-sessions' : 'Half-day, full day or multi-session programme' ?>
                </p>
                <ul class="tarif-card__list">
                    <li><?= $isFr ? 'Bons usages de l\'IA au quotidien (ChatGPT, Claude, Copilot…)' : 'Practical daily AI use (ChatGPT, Claude, Copilot…)' ?></li>
                    <li><?= $isFr ? 'Programme sur mesure selon votre secteur et vos outils' : 'Customised programme tailored to your sector and tools' ?></li>
                    <li><?= $isFr ? 'Présentiel toute la France — Grand Ouest, Suisse, Luxembourg' : 'On-site across France — West region, Switzerland, Luxembourg' ?></li>
                    <li><?= $isFr ? 'Groupes de 4 à 20 personnes, supports de formation fournis' : 'Groups of 4 to 20, training materials included' ?></li>
                    <li><?= $isFr ? 'Suivi optionnel après la session' : 'Optional post-training follow-up' ?></li>
                </ul>
                <div class="tarif-card__ideal">
                    <strong><?= $isFr ? 'Idéal pour :' : 'Ideal for:' ?></strong>
                    <?= $isFr
                        ? 'PME, associations, collectivités et équipes non-techniques souhaitant monter en compétence sur l\'IA sans jargon.'
                        : 'SMBs, associations, public bodies and non-technical teams looking to upskill on AI without the jargon.' ?>
                </div>
                <a href="<?= $base ?>/contact" class="btn btn--outline btn--full">
                    <?= $isFr ? 'Demander un devis →' : 'Request a quote →' ?>
                </a>
            </article>

            <!-- ── OPTION 5 : STAGE ── -->
            <article class="tarif-card" id="stage">
                <div class="tarif-card__badge">
                    <span class="tag tag--green"><?= $isFr ? 'Remote & Bretagne' : 'Remote & Brittany' ?></span>
                </div>
                <p class="tarif-card__type"><?= $isFr ? 'Stage — convention obligatoire' : 'Internship — agreement required' ?></p>
                <div class="tarif-card__price">
                    <span class="tarif-card__amount"><?= $isFr ? '6 mois min.' : '6 months min.' ?></span>
                    <span class="tarif-card__unit"><?= $isFr ? 'temps plein' : 'full time' ?></span>
                </div>
                <p class="tarif-card__forfait">
                    <?= $isFr ? 'Remote ou présentiel Bretagne · Convention de stage requise' : 'Remote or on-site Brittany · Internship agreement required' ?>
                </p>
                <ul class="tarif-card__list">
                    <li><?= $isFr ? 'Participation à des projets réels et ambitieux' : 'Involvement in real, ambitious projects' ?></li>
                    <li><?= $isFr ? 'Montée en compétences sur PHP, Python, IA appliquée' : 'Skills development in PHP, Python, applied AI' ?></li>
                    <li><?= $isFr ? 'Échanges réguliers, feedback et suivi personnalisé' : 'Regular exchanges, feedback and personal mentoring' ?></li>
                    <li><?= $isFr ? 'Environnement de travail structuré et bienveillant' : 'Structured and supportive work environment' ?></li>
                    <li><?= $isFr ? 'Gratification légale en vigueur' : 'Statutory internship allowance applies' ?></li>
                </ul>
                <div class="tarif-card__ideal">
                    <strong><?= $isFr ? 'Idéal pour :' : 'Ideal for:' ?></strong>
                    <?= $isFr
                        ? 'Étudiant·e en développement web souhaitant progresser sur des projets concrets. Aussi ouvert aux startups et TPE/PME cherchant à former et intégrer un profil junior avec l\'encadrement d\'une développeuse senior.'
                        : 'Web development students looking to grow on real projects. Also open to startups and SMBs wanting to train and integrate a junior profile under senior developer mentorship.' ?>
                </div>
                <a href="<?= $base ?>/contact" class="btn btn--outline btn--full">
                    <?= $isFr ? 'Me contacter →' : 'Get in touch →' ?>
                </a>
            </article>

        </div>

        <!-- Disclaimer -->
        <p class="tarifs-disclaimer">
            <?= $isFr
                ? 'Les propositions CDI sont examinées au cas par cas — projet technique, vision produit, équipe. Je n\'accepte pas toutes les offres. Un premier échange de 20 min permet de qualifier rapidement.'
                : 'CDI proposals are considered case by case — technical project, product vision, team. I do not accept every offer. A 20-min initial call allows rapid qualification.' ?>
        </p>
    </div>
</section>

<!-- ─── TABLEAU COMPARATIF ────────────────────────────────── -->
<section class="section tarifs-compare">
    <div class="section__inner">
        <p class="eyebrow"><?= $isFr ? 'COMPARATIF' : 'COMPARISON' ?></p>
        <h2 class="section__title"><?= $isFr ? 'Quelle formule pour quel projet ?' : 'Which model for which project?' ?></h2>
        <div class="tarifs-compare__table-wrap">
            <table class="tarifs-compare__table">
                <thead>
                    <tr>
                        <th><?= $isFr ? 'Critère' : 'Criterion' ?></th>
                        <th><?= $isFr ? 'Freelance' : 'Freelance' ?></th>
                        <th><?= $isFr ? 'CDI Remote' : 'Full-time CDI' ?></th>
                        <th><?= $isFr ? 'CDI Mi-temps' : 'Part-time CDI' ?></th>
                        <th><?= $isFr ? 'Formation IA' : 'AI Training' ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $isFr ? 'Engagement' : 'Commitment' ?></td>
                        <td><?= $isFr ? 'Ponctuel / projet' : 'One-off / project' ?></td>
                        <td><?= $isFr ? 'Long terme (CDI)' : 'Long-term (CDI)' ?></td>
                        <td><?= $isFr ? 'Long terme (CDI)' : 'Long-term (CDI)' ?></td>
                        <td><?= $isFr ? 'Ponctuel / session' : 'One-off / session' ?></td>
                    </tr>
                    <tr>
                        <td><?= $isFr ? 'Disponibilité' : 'Availability' ?></td>
                        <td><?= $isFr ? 'Sur le projet' : 'Per project' ?></td>
                        <td>35h / sem.</td>
                        <td>17h30 / sem.</td>
                        <td><?= $isFr ? 'Sur devis' : 'On quote' ?></td>
                    </tr>
                    <tr>
                        <td><?= $isFr ? 'Coût mensuel' : 'Monthly cost' ?></td>
                        <td><?= $isFr ? '~12 000–16 000 € HT' : '~€12,000–16,000 excl. VAT' ?></td>
                        <td><?= $isFr ? 'Salaire + charges' : 'Salary + employer costs' ?></td>
                        <td><?= $isFr ? '50 % salaire + charges' : '50% salary + employer costs' ?></td>
                        <td><?= $isFr ? 'Forfait sur devis' : 'Fixed-price quote' ?></td>
                    </tr>
                    <tr>
                        <td><?= $isFr ? 'Propriété du code' : 'Code ownership' ?></td>
                        <td><?= $isFr ? 'Client (livraison)' : 'Client (delivered)' ?></td>
                        <td><?= $isFr ? 'Employeur (de droit)' : 'Employer (by law)' ?></td>
                        <td><?= $isFr ? 'Employeur (de droit)' : 'Employer (by law)' ?></td>
                        <td><?= $isFr ? 'N/A — formation' : 'N/A — training' ?></td>
                    </tr>
                    <tr>
                        <td><?= $isFr ? 'Délai de démarrage' : 'Start timeline' ?></td>
                        <td><?= $isFr ? '2-4 semaines' : '2-4 weeks' ?></td>
                        <td><?= $isFr ? '1-3 mois (préavis)' : '1-3 months (notice)' ?></td>
                        <td><?= $isFr ? '1-3 mois (préavis)' : '1-3 months (notice)' ?></td>
                        <td><?= $isFr ? '2-4 semaines' : '2-4 weeks' ?></td>
                    </tr>
                    <tr>
                        <td><?= $isFr ? 'Intégration équipe' : 'Team integration' ?></td>
                        <td><?= $isFr ? 'Partielle' : 'Partial' ?></td>
                        <td><?= $isFr ? 'Complète' : 'Full' ?></td>
                        <td><?= $isFr ? 'Partielle (jours dédiés)' : 'Partial (dedicated days)' ?></td>
                        <td><?= $isFr ? 'Formation de groupe (4–20)' : 'Group training (4–20)' ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ─── FAQ TARIFS ────────────────────────────────────────── -->
<section class="section faq" id="faq-tarifs">
    <div class="section__inner">
        <div class="section__head">
            <div>
                <p class="eyebrow"><?= $isFr ? 'QUESTIONS FRÉQUENTES' : 'FAQ' ?></p>
                <h2 class="section__title"><?= $isFr ? 'Ce que tout le monde demande.' : 'What everyone asks.' ?></h2>
            </div>
        </div>
        <div class="faq__list">
            <?php
            $faqs = $isFr ? [
                ['Quand choisir le freelance plutôt qu\'embaucher ?',
                 'Le freelance est idéal pour un besoin ponctuel, un MVP à lancer ou une mission technique délimitée. Le CDI s\'impose quand le besoin est permanent et que l\'intégration dans l\'équipe produit est stratégique.'],
                ['Qu\'est-ce qu\'un CDI remote full-time concrètement ?',
                 'Un contrat salarié classique (35h/semaine) avec télétravail 100 %. Pas de déménagement, pas de présentiel imposé. Disponibilité complète dans votre stack, vos outils et vos rituels d\'équipe.'],
                ['Qu\'est-ce qu\'un CDI mi-temps (50 %) ?',
                 'Un contrat salarié à 17h30 par semaine. Idéal pour les startups early-stage qui ont besoin d\'une vraie expertise tech sans embaucher à plein temps. Je consacre l\'autre moitié à d\'autres engagements.'],
                ['Quelle est la différence légale entre prestataire et salarié ?',
                 'En tant que prestataire, je suis indépendante — vous êtes client, pas employeur. En CDI, vous devenez employeur (charges sociales, mutuelle, congés). Le choix dépend de votre besoin en termes d\'engagement et de dépendance économique.'],
                ['Le TJM est-il négociable ?',
                 'Le TJM varie entre 600 et 800 €/j selon la durée (plus long = plus prévisible), la complexité technique et la présence d\'IA dans le scope. Forfait possible à partir de 2 semaines, sans surprise sur la facture finale.'],
                ['Quelles sont vos prétentions salariales en CDI ?',
                 'Disponibles sur demande après un premier échange pour cadrer le poste. Je ne communique pas de fourchette sans comprendre le périmètre réel, les avantages et la situation de l\'entreprise.'],
            ] : [
                ['When should I choose freelance over hiring?',
                 'Freelance is ideal for a defined technical need, an MVP to launch or a scoped mission. A CDI makes sense when the need is permanent and technical integration into your product team is strategic.'],
                ['What does a full-time remote CDI look like in practice?',
                 'A standard employment contract (35h/week) with 100% remote work. No relocation, no mandatory on-site. Full availability within your stack, tooling and team rituals.'],
                ['What is a part-time CDI (50%)?',
                 'An employment contract at 17h30 per week. Ideal for early-stage startups that need real technical expertise without hiring full-time. I dedicate the other half of my week to other engagements.'],
                ['What is the legal difference between a contractor and an employee?',
                 'As a freelance contractor, I am self-employed — you are a client, not an employer. Under a CDI, you become an employer with associated obligations (social charges, health insurance, paid leave).'],
                ['Is the day rate negotiable?',
                 'The day rate ranges from €600 to €800/day depending on mission length, technical complexity and whether AI is in scope. Fixed-price quotes available from 2 weeks of work, no surprise on the final invoice.'],
                ['What are your salary expectations for a CDI?',
                 'Available on request after an initial call to frame the role. I do not communicate a salary range without first understanding the real scope, benefits and company situation.'],
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

<!-- ─── ZONES D'INTERVENTION — maillage SEO local + UX claire ─────────── -->
<section class="section zones-section" id="zones-section">
    <div class="section__inner">
        <p class="eyebrow"><?= $isFr ? 'ZONES D\'INTERVENTION' : 'AREAS SERVED' ?></p>
        <h2 class="section__title">
            <?= $isFr
                ? 'Basée à Vannes, je travaille en remote dans toute l\'Europe francophone.'
                : 'Based in Vannes, I work remotely across French-speaking Europe.' ?>
        </h2>
        <p class="zones-section__intro">
            <?= $isFr
                ? 'Mes clients sont en France, Suisse romande et au Luxembourg. Réponse sous 24h, démarrage sous 2-4 semaines. Cliquez sur une ville pour voir le contexte local.'
                : 'My clients are in France, French-speaking Switzerland and Luxembourg. Reply within 24h, start within 2-4 weeks. Click a city to see the local context.' ?>
        </p>

        <?php
        $zones = $isFr ? [
            'Grand Ouest — Bretagne'      => ['rennes' => 'Rennes', 'nantes' => 'Nantes', 'vannes' => 'Vannes', 'brest' => 'Brest', 'quimper' => 'Quimper', 'lorient' => 'Lorient', 'saint-brieuc' => 'Saint-Brieuc', 'saint-malo' => 'Saint-Malo'],
            'Grand Ouest — Pays de la Loire' => ['angers' => 'Angers', 'le-mans' => 'Le Mans'],
            'Paris'                       => ['paris' => 'Paris'],
            'Sud-Ouest'                   => ['bordeaux' => 'Bordeaux', 'toulouse' => 'Toulouse', 'bayonne' => 'Bayonne', 'biarritz' => 'Biarritz'],
            'Frontalier Haute-Savoie'     => ['annecy' => 'Annecy', 'annemasse' => 'Annemasse', 'thonon-les-bains' => 'Thonon-les-Bains'],
            'Luxembourg'                  => ['luxembourg' => 'Luxembourg-Ville'],
            'Suisse romande'              => ['geneve' => 'Genève', 'lausanne' => 'Lausanne'],
        ] : [
            'Western France — Brittany'      => ['rennes' => 'Rennes', 'nantes' => 'Nantes', 'vannes' => 'Vannes', 'brest' => 'Brest', 'quimper' => 'Quimper', 'lorient' => 'Lorient', 'saint-brieuc' => 'Saint-Brieuc', 'saint-malo' => 'Saint-Malo'],
            'Western France — Pays de la Loire' => ['angers' => 'Angers', 'le-mans' => 'Le Mans'],
            'Paris'                          => ['paris' => 'Paris'],
            'South-West France'              => ['bordeaux' => 'Bordeaux', 'toulouse' => 'Toulouse', 'bayonne' => 'Bayonne', 'biarritz' => 'Biarritz'],
            'Haute-Savoie border'            => ['annecy' => 'Annecy', 'annemasse' => 'Annemasse', 'thonon-les-bains' => 'Thonon-les-Bains'],
            'Luxembourg'                     => ['luxembourg' => 'Luxembourg City'],
            'French-speaking Switzerland'    => ['geneve' => 'Geneva', 'lausanne' => 'Lausanne'],
        ];
        ?>

        <div class="zones-grid">
            <?php $idx = 0; foreach ($zones as $zoneName => $cities): ?>
            <details class="zones-item" <?= $idx === 0 ? 'open' : '' ?>>
                <summary class="zones-item__header">
                    <span class="zones-item__name"><?= htmlspecialchars($zoneName) ?></span>
                    <span class="zones-item__chev" aria-hidden="true">+</span>
                </summary>
                <ul class="zones-item__cities">
                    <?php foreach ($cities as $slug => $label): ?>
                    <li>
                        <a href="<?= $base ?>/dev-freelance/<?= $slug ?>"><?= htmlspecialchars($label) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </details>
            <?php $idx++; endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── CTA BAND ──────────────────────────────────────────── -->
<section class="cta-band">
    <p class="eyebrow"><?= $isFr ? 'TRAVAILLONS ENSEMBLE' : 'LET\'S WORK TOGETHER' ?></p>
    <h2 class="cta-band__title"><?= $isFr ? 'Discutons de votre projet.' : 'Let\'s talk about your project.' ?></h2>
    <p class="cta-band__sub">
        <?= $isFr
            ? 'Premier échange de 20-30 min pour qualifier le besoin et choisir la bonne formule. Réponse sous 24h.'
            : '20-30 min initial call to qualify the need and pick the right model. Reply within 24h.' ?>
    </p>
    <a href="<?= $base ?>/contact" class="btn btn--dark btn--lg">
        <?= $isFr ? 'Écrire à Sonia →' : 'Write to Sonia →' ?>
    </a>
</section>
