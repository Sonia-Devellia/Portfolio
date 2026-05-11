<?php
/**
 * Vue — étude de cas Amanéa Voyages.
 *
 * @var callable(string): string $t
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
$lang = $_SESSION['lang'] ?? 'fr';

if ($lang === 'fr') {
    $case = [
        'eyebrow' => "Étude de cas · Amanéa Voyages",
        'title'   => "Un site sur mesure pour une agence qui voulait reprendre la main.",
        'lede'    => "Comment construire un site institutionnel bilingue avec backoffice, espace client et gestion des réservations — sans CMS, sans plugins, avec un code que l'équipe peut maintenir à long terme.",
        'meta' => [
            ['Stack',    'PHP 8 MVC · SCSS · JS · MySQL'],
            ['Durée',    '8 semaines'],
            ["Résultat", "Autonomie totale de l'équipe"],
        ],
        'chapters' => [
            [
                'contexte',
                "Le contexte",
                "Amanéa est une agence de voyages qui propose des séjours sur mesure. Le site existant tournait sous WordPress avec une accumulation de plugins difficiles à maintenir. L'équipe ne pouvait pas modifier le catalogue sans passer par un prestataire externe. Il n'y avait pas d'espace client, pas de suivi de réservation, et chaque mise à jour de contenu prenait une journée et une facture. La demande était directe : reprendre la main.",
            ],
            [
                'choix',
                "Pourquoi du MVC sans CMS",
                "La première discussion portait sur WordPress. Rapide à démarrer, communauté large. Mais obtenir une gestion de réservations propre, un backoffice de destinations, un espace client sécurisé et une interface bilingue sans empilement de plugins s'avérait impossible sans recréer exactement ce qu'on voulait éviter. Un MVC PHP natif, léger, sans dépendance lourde, laissait toute l'architecture dans les mains du projet — pas d'un écosystème tiers à surveiller.",
            ],
            [
                'architecture',
                "L'architecture choisie",
                "PHP 8 MVC natif avec autoload PSR-4. Base MySQL relationnelle avec tables séparées pour les destinations, les offres, les clients et les réservations. Routing manuel, controllers dédiés : Accueil, Auth, Destination, Offre, Réservation, Backoffice. Aucun framework — chaque couche connue, chaque ligne maîtrisée. La structure reflète exactement ce que le site fait, et rien de plus.",
            ],
            [
                'backoffice',
                "Le backoffice",
                "L'équipe Amanéa gère l'intégralité du contenu sans aucune aide technique. L'interface permet de créer, modifier et archiver des destinations, d'y rattacher des offres avec dates, prix et disponibilités, et d'uploader des visuels directement depuis le navigateur. La gestion des réservations entrantes — avec statuts en attente, confirmée, annulée — est centralisée dans le même espace. Aucune ligne de code à toucher pour une mise à jour courante.",
            ],
            [
                'espaceclient',
                "L'espace client",
                "Chaque client dispose d'un compte sécurisé. Après réservation, il accède à un tableau de bord : statut en temps réel de sa commande, récapitulatif du séjour, et téléchargement du carnet de voyage en PDF. L'authentification repose sur des sessions PHP avec hachage bcrypt, sans dépendance externe. La gestion de session est cloisonnée : les cookies sont httponly, les identifiants jamais stockés en clair.",
            ],
            [
                'bilingue',
                "Le bilingue FR/EN",
                "La traduction est gérée par un système i18n natif : fichiers de langue indexés par clé, langue active en session. Chaque page est accessible dans les deux langues sans duplication de routes. Les balises meta — title, description, og:locale — sont générées dynamiquement. Les hreflang sont injectés dans le layout pour que Google comprenne la relation entre les deux versions du contenu.",
            ],
            [
                'charte',
                "La charte typographique",
                "L'agence avait une identité existante mais peu exploitée sur le web. Une fonte serif pour les titres, une sans-serif pour le corps, une palette chaude. Le travail consistait à traduire cette charte en variables CSS cohérentes, à établir une hiérarchie typographique stricte, et à intégrer les visuels des destinations — photos plein format, ambiance — sans alourdir le temps de chargement. Toutes les images sont servies en WebP avec lazy loading.",
            ],
            [
                'production',
                "La mise en production",
                "Déploiement sur hébergement Apache mutualisé. Configuration htaccess pour le routing MVC, désactivation du listing de répertoires, cache navigateur longue durée sur les assets statiques, compression Gzip. Variables d'environnement pour les secrets. Les erreurs PHP sont masquées en production et redirigées vers un log applicatif. La maintenance courante — mises à jour de contenu — est traitée sous 48h.",
            ],
            [
                'retenu',
                "Ce que j'ai retenu",
                "Sur Amanéa, la valeur n'était pas dans la complexité technique mais dans l'adéquation entre l'architecture et les usages réels. L'équipe modifie son catalogue seule. Les clients retrouvent leurs réservations sans friction. Le code est suffisamment lisible pour qu'une autre main puisse s'en emparer. Ces trois critères — autonomie de l'équipe, clarté pour le client final, maintenabilité — sont devenus la grille que j'applique à chaque projet.",
            ],
        ],
        'disclaimer' => "Projet réel développé et mis en production pour l'agence Amanéa Voyages. Les données clients et les chiffres internes sont confidentiels ; les éléments présentés ici ont été validés pour publication.",
    ];
} else {
    $case = [
        'eyebrow' => "Case study · Amanéa Voyages",
        'title'   => "A bespoke website for an agency that wanted to take back control.",
        'lede'    => "How to build a bilingual institutional website with a back-office, client portal and booking management — no CMS, no plugins, with code the team can maintain long-term.",
        'meta' => [
            ['Stack',    'PHP 8 MVC · SCSS · JS · MySQL'],
            ['Timeline', '8 weeks'],
            ['Outcome',  'Full team autonomy'],
        ],
        'chapters' => [
            [
                'context',
                "The context",
                "Amanéa is a travel agency offering bespoke holidays. The existing site ran on WordPress with a pile of plugins that were becoming hard to manage. The team could not update the catalogue without going through an external developer. There was no client portal, no booking tracking, and every content update took a day and an invoice. The brief was straightforward: take back control.",
            ],
            [
                'choice',
                "Why MVC without a CMS",
                "The first conversation was about WordPress. Quick to start, wide community. But getting clean booking management, a destinations back-office, a secure client area and a bilingual interface without stacking plugins turned out to be impossible without recreating exactly what we wanted to avoid. A native PHP MVC, lightweight, with no heavy dependencies, kept the entire architecture in the project's hands — not tied to a third-party ecosystem to monitor.",
            ],
            [
                'architecture',
                "The chosen architecture",
                "PHP 8 native MVC with PSR-4 autoloading. Relational MySQL database with separate tables for destinations, offers, clients and bookings. Manual routing, dedicated controllers: Home, Auth, Destination, Offer, Booking, Back-office. No framework — every layer known, every line controlled. The structure reflects exactly what the site does, and nothing more.",
            ],
            [
                'backoffice',
                "The back-office",
                "The Amanéa team manages all content without any technical help. The interface allows creating, editing and archiving destinations, attaching offers with dates, prices and availability, and uploading visuals directly from the browser. Incoming booking management — with pending, confirmed and cancelled statuses — is centralised in the same space. No code to touch for a routine update.",
            ],
            [
                'clientportal',
                "The client portal",
                "Each client has a secure account. After booking, they access a dashboard: real-time order status, trip summary, and itinerary PDF download. Authentication uses PHP sessions with bcrypt hashing, no external dependency. Session management is scoped: cookies are httponly, credentials never stored in plain text.",
            ],
            [
                'bilingual',
                "FR/EN bilingual",
                "Translation is handled by a native i18n system: language files indexed by key, active language in session. Every page is reachable in both languages without route duplication. Meta tags — title, description, og:locale — are generated dynamically. Hreflang tags are injected in the layout so Google understands the relationship between both content versions.",
            ],
            [
                'identity',
                "The typographic identity",
                "The agency had an existing brand that was underused on the web. A serif font for headings, a sans-serif for body, a warm palette. The work was to translate that identity into consistent CSS variables, establish a strict heading hierarchy, and integrate destination visuals — full-frame photos, mood — without slowing load time. All images are served in WebP with lazy loading.",
            ],
            [
                'production',
                "Going live",
                "Deployed on shared Apache hosting. htaccess configuration for MVC routing, directory listing disabled, long-term browser cache on static assets, Gzip compression. Environment variables for secrets. PHP errors are suppressed in production and redirected to an application log. Routine maintenance — content updates — is handled within 48 hours.",
            ],
            [
                'learning',
                "What I took away",
                "On Amanéa, the value was not in technical complexity but in the match between the architecture and actual usage. The team edits their catalogue on their own. Clients find their bookings without friction. The code is readable enough for another developer to pick up. Those three criteria — team autonomy, end-user clarity, maintainability — became the grid I now apply to every project.",
            ],
        ],
        'disclaimer' => "A real project developed and deployed for Amanéa Voyages travel agency. Client data and internal figures are confidential; the elements presented here have been approved for publication.",
    ];
}
?>

<article class="case-study">
    <a href="<?= $base ?>/" class="case-study__back"><?= $t('case.back') ?></a>

    <header class="case-study__head">
        <p class="eyebrow"><?= htmlspecialchars($case['eyebrow']) ?></p>
        <h1 class="case-study__title"><?= htmlspecialchars($case['title']) ?></h1>
        <p class="case-study__lede"><?= htmlspecialchars($case['lede']) ?></p>
        <dl class="case-study__meta">
            <?php foreach ($case['meta'] as [$key, $value]): ?>
            <div>
                <dt><?= htmlspecialchars($key) ?></dt>
                <dd><?= htmlspecialchars($value) ?></dd>
            </div>
            <?php endforeach; ?>
        </dl>
    </header>

    <nav class="case-study__toc" aria-label="<?= $lang === 'fr' ? "Sommaire de l'étude de cas" : 'Case study table of contents' ?>">
        <?php foreach ($case['chapters'] as $index => [$id, $title]): ?>
        <a href="#<?= htmlspecialchars($id) ?>">
            <span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <?= htmlspecialchars($title) ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- Stats row -->
    <div class="case-study__stats">
        <div class="case-study__stat">
            <span class="case-study__stat-num">FR<span>/EN</span></span>
            <span class="case-study__stat-lbl"><?= $lang === 'fr' ? 'Bilingue natif' : 'Native bilingual' ?></span>
        </div>
        <div class="case-study__stat">
            <span class="case-study__stat-num">5<span> mod.</span></span>
            <span class="case-study__stat-lbl"><?= $lang === 'fr' ? 'Modules métier' : 'Business modules' ?></span>
        </div>
        <div class="case-study__stat">
            <span class="case-study__stat-num">0<span> plugin</span></span>
            <span class="case-study__stat-lbl"><?= $lang === 'fr' ? 'MVC pur, sans CMS' : 'Pure MVC, no CMS' ?></span>
        </div>
    </div>

    <div class="case-study__body">
        <?php foreach ($case['chapters'] as $index => [$id, $title, $body]): ?>
        <section id="<?= htmlspecialchars($id) ?>" class="case-study__section">
            <span class="case-study__num"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <div>
                <h2><?= htmlspecialchars($title) ?></h2>
                <p><?= htmlspecialchars($body) ?></p>
            </div>
        </section>
        <?php if ($index === 2): ?>
        <!-- Architecture diagram — après le chapitre Architecture -->
        <div class="case-study__diagram">
            <p class="case-study__diagram-lbl"><?= $lang === 'fr' ? 'Architecture MVC — modules et flux' : 'MVC architecture — modules and flow' ?></p>
            <svg viewBox="0 0 800 240" xmlns="http://www.w3.org/2000/svg" role="img"
                 aria-label="<?= $lang === 'fr' ? 'Diagramme architecture MVC Amanéa' : 'Amanéa MVC architecture diagram' ?>">
                <defs>
                    <marker id="arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="7" markerHeight="7" orient="auto">
                        <path d="M0,0 L10,5 L0,10 z" fill="currentColor"/>
                    </marker>
                </defs>
                <style>
                    .dg-box        { fill: var(--bg-subtle, #f0eeeb); stroke: var(--border-md); stroke-width: 1; }
                    .dg-box-soft   { fill: var(--bg-soft, #f7f6f4); stroke: var(--text-3, #9a9895); stroke-width: 1; stroke-dasharray: 3 3; }
                    .dg-box-accent { fill: var(--accent-soft, #e0deff); stroke: var(--accent, #0F03A0); stroke-width: 1.5; }
                    .dg-t          { font-family: -apple-system, system-ui, sans-serif; font-size: 12px; fill: var(--text, #111110); }
                    .dg-t-mono     { font-family: ui-monospace, Menlo, monospace; font-size: 9.5px; fill: var(--text-3, #9a9895); letter-spacing: 0.04em; text-transform: uppercase; }
                    .dg-l          { stroke: var(--text-2, #5a5956); stroke-width: 1; fill: none; marker-end: url(#arrow); }
                    .dg-l-soft     { stroke: var(--text-3, #9a9895); stroke-width: 1; stroke-dasharray: 4 3; fill: none; marker-end: url(#arrow); }
                </style>
                <!-- Router -->
                <rect class="dg-box-accent" x="20" y="94" width="130" height="52"/>
                <text class="dg-t-mono" x="32" y="112">Router</text>
                <text class="dg-t" x="32" y="130" style="font-weight:500">Front controller</text>
                <!-- Arrows to controllers -->
                <line class="dg-l" x1="150" y1="104" x2="218" y2="60"/>
                <line class="dg-l" x1="150" y1="110" x2="218" y2="120"/>
                <line class="dg-l" x1="150" y1="130" x2="218" y2="180"/>
                <!-- Controllers -->
                <rect class="dg-box" x="228" y="36" width="130" height="48"/>
                <text class="dg-t-mono" x="240" y="54">Front</text>
                <text class="dg-t" x="240" y="72">Accueil · Destinations</text>
                <rect class="dg-box" x="228" y="96" width="130" height="48"/>
                <text class="dg-t-mono" x="240" y="114">Auth · Client</text>
                <text class="dg-t" x="240" y="132">Réservations · PDF</text>
                <rect class="dg-box" x="228" y="156" width="130" height="48"/>
                <text class="dg-t-mono" x="240" y="174">Backoffice</text>
                <text class="dg-t" x="240" y="192">CRUD · Offres · Médias</text>
                <!-- Arrows to MySQL -->
                <line class="dg-l" x1="358" y1="60" x2="450" y2="110"/>
                <line class="dg-l" x1="358" y1="120" x2="450" y2="120"/>
                <line class="dg-l" x1="358" y1="180" x2="450" y2="130"/>
                <!-- MySQL -->
                <rect class="dg-box" x="460" y="80" width="130" height="80"/>
                <text class="dg-t-mono" x="472" y="98">MySQL</text>
                <text class="dg-t" x="472" y="116">destinations</text>
                <text class="dg-t" x="472" y="132">clients · réservations</text>
                <text class="dg-t" x="472" y="148">offres · médias</text>
                <!-- Outputs -->
                <line class="dg-l-soft" x1="590" y1="110" x2="640" y2="70"/>
                <line class="dg-l-soft" x1="590" y1="130" x2="640" y2="170"/>
                <rect class="dg-box-soft" x="650" y="46" width="130" height="48"/>
                <text class="dg-t-mono" x="662" y="64">i18n</text>
                <text class="dg-t" x="662" y="82">FR / EN — sessions</text>
                <rect class="dg-box-soft" x="650" y="146" width="130" height="48"/>
                <text class="dg-t-mono" x="662" y="164">Views · SCSS</text>
                <text class="dg-t" x="662" y="182">Charte · WebP · PDF</text>
            </svg>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <footer class="case-study__disclaimer">
        <?= htmlspecialchars($case['disclaimer']) ?>
    </footer>
</article>
