<?php
/**
 * Vue — étude de cas triage support.
 *
 * @var callable(string): string $t
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
$lang = $_SESSION['lang'] ?? 'fr';

if ($lang === 'fr') {
    $case = [
        'eyebrow' => 'Étude de cas · Triage support N1',
        'title' => 'Trier 800 tickets/jour sans embaucher.',
        'lede' => 'Comment une couche IA bien posée peut remplacer deux ETP de tri N1 chez une PME SaaS de 30 personnes, et pourquoi le projet devait commencer par une architecture classique plutôt que par un chatbot.',
        'meta' => [
            ['Stack', 'PHP 8 · Python · OpenAI API'],
            ['Durée', '6 semaines'],
            ['Résultat', '-40% temps moyen ticket'],
        ],
        'chapters' => [
            ['contexte', 'Le contexte', 'Une PME SaaS B2B reçoit plusieurs centaines de demandes support par jour. La pile existe déjà : helpdesk, CRM, base clients, application PHP. Le problème n’est pas le manque d’outils, mais la charge humaine créée par le tri initial. Chaque ticket doit être lu, qualifié, priorisé, puis envoyé à la bonne personne. Le recrutement d’un second profil support N1 est envisagé, mais la marge rend la décision difficile.'],
            ['refus', 'Pourquoi j’ai failli refuser', 'Le premier brief demandait un chatbot. Mauvaise question. Un chatbot aurait ajouté une interface visible, difficile à maintenir et probablement frustrante pour les clients. Le vrai besoin n’était pas de répondre à leur place, mais de réduire le temps perdu avant qu’un humain compétent intervienne. Cette clarification change toute l’architecture.'],
            ['probleme', 'Le vrai problème', 'L’analyse d’un échantillon montre trois familles : environ 30% des tickets relèvent de FAQ, 50% nécessitent un humain, 20% restent ambigus. La valeur n’est pas dans une réponse automatique totale. Elle est dans le routage, la qualification et la suggestion. Le système doit accélérer le support sans masquer les zones de doute.'],
            ['architecture', 'L’architecture choisie', 'Pas un chatbot. Un classifieur et un routeur. Le webhook helpdesk déclenche un service Python qui nettoie le ticket, interroge un modèle avec un prompt versionné, puis renvoie une catégorie, un niveau de confiance et une suggestion. PHP conserve la logique métier, l’audit trail et les règles de fallback. Le LLM aide, mais ne décide pas seul.'],
            ['tests', 'Ce que j’ai testé avant de coder', 'Avant l’intégration, un eval set de 200 tickets annotés sert de référence. Une baseline par règles mesure ce que l’on peut faire sans IA. Ensuite seulement, le modèle est comparé sur précision, coût, stabilité et erreurs dangereuses. Si l’amélioration n’est pas nette, la couche IA est refusée.'],
            ['integration', 'L’intégration', 'Le flux final reste simple : webhook helpdesk, classifieur, tag, suggestion, validation humaine. Les tickets à forte confiance sont pré-triés. Les tickets ambigus restent visibles. Les agents support peuvent corriger la catégorie, ce qui alimente les jeux d’évaluation futurs.'],
            ['guardrails', 'Les garde-fous', 'Budget tokens mensuel, logs de prompts, version de modèle figée, kill switch, fallback déterministe. Un système IA en production doit pouvoir être coupé sans casser l’application. C’est cette contrainte qui sépare une intégration sérieuse d’une démo impressionnante.'],
            ['resultats', 'Les résultats', 'Le temps moyen de traitement baisse d’environ 40% sur le tri initial. Le coût mensuel IA reste inférieur à un huitième d’un salaire chargé. Le gain principal n’est pas seulement financier : le support récupère de l’attention pour les cas complexes.'],
            ['appris', 'Ce que je referais autrement', 'J’ajouterais plus tôt une interface d’audit pour relire les décisions du modèle par période. Les logs existaient, mais une vue métier dédiée aurait accéléré les arbitrages avec l’équipe support. Le point n’est pas de rendre l’IA invisible, mais de la rendre gouvernable.'],
        ],
        'disclaimer' => 'Ce cas consolide plusieurs situations clientes et scénarios techniques sous NDA. Les chiffres, contraintes et décisions d’architecture sont réalistes ; les noms et le contexte ont été modifiés.',
    ];
} else {
    $case = [
        'eyebrow' => 'Case study · Support L1 triage',
        'title' => 'Triage 800 tickets/day without hiring.',
        'lede' => 'How a properly scoped AI layer can replace two L1 triage roles in a 30-person B2B SaaS company, and why the project had to start with classic architecture instead of a chatbot.',
        'meta' => [
            ['Stack', 'PHP 8 · Python · OpenAI API'],
            ['Timeline', '6 weeks'],
            ['Outcome', '-40% average ticket handling time'],
        ],
        'chapters' => [
            ['context', 'The context', 'A B2B SaaS SME receives several hundred support requests per day. The stack already exists: helpdesk, CRM, customer database, PHP application. The issue is not tooling, but the human load created by first-level triage. Every ticket has to be read, qualified, prioritized and routed. Hiring another L1 support profile is considered, but margins make the decision difficult.'],
            ['refusal', 'Why I nearly refused', 'The first brief asked for a chatbot. Wrong question. A chatbot would have added a visible interface, hard to maintain and probably frustrating for customers. The real need was not to answer instead of support, but to reduce wasted time before the right human intervenes. That changed the architecture.'],
            ['problem', 'The real problem', 'A sample analysis shows three groups: around 30% FAQ tickets, 50% human-required cases, 20% ambiguous. The value is not in full automation. It is in routing, qualification and suggestion. The system should accelerate support without hiding uncertainty.'],
            ['architecture', 'The chosen architecture', 'Not a chatbot. A classifier and a router. The helpdesk webhook triggers a Python service that cleans the ticket, calls a model with a versioned prompt, then returns a category, confidence level and suggestion. PHP keeps business logic, audit trail and fallback rules. The LLM helps, but does not decide alone.'],
            ['tests', 'What I tested before coding', 'Before integration, a 200-ticket annotated eval set acts as reference. A rule-based baseline measures what can be done without AI. Only then is the model compared on precision, cost, stability and dangerous errors. If the improvement is not clear, the AI layer is refused.'],
            ['integration', 'The integration', 'The final flow stays simple: helpdesk webhook, classifier, tag, suggestion, human validation. High-confidence tickets are pre-triaged. Ambiguous tickets stay visible. Support agents can correct the category, feeding future evaluation sets.'],
            ['guardrails', 'The guardrails', 'Monthly token budget, prompt logs, pinned model version, kill switch, deterministic fallback. An AI system in production must be removable without breaking the application. That constraint separates serious integration from an impressive demo.'],
            ['outcomes', 'The results', 'Average triage handling time drops by about 40%. Monthly AI cost remains below one eighth of a fully loaded salary. The main gain is not only financial: support recovers attention for complex cases.'],
            ['learning', 'What I would do differently', 'I would add an audit interface earlier to review model decisions by period. Logs existed, but a dedicated business view would have accelerated decisions with the support team. The point is not to make AI invisible, but governable.'],
        ],
        'disclaimer' => 'This case consolidates several client situations and technical scenarios under NDA. Figures, constraints and architecture decisions are realistic; names and context have been changed.',
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

    <nav class="case-study__toc" aria-label="<?= $lang === 'fr' ? 'Sommaire de l’étude de cas' : 'Case study table of contents' ?>">
        <?php foreach ($case['chapters'] as $index => [$id, $title]): ?>
        <a href="#<?= htmlspecialchars($id) ?>">
            <span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <?= htmlspecialchars($title) ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- Stats row — résultats clés avant le corps -->
    <div class="case-study__stats">
        <div class="case-study__stat">
            <span class="case-study__stat-num">–40<span>%</span></span>
            <span class="case-study__stat-lbl"><?= $lang === 'fr' ? 'Temps moyen ticket' : 'Avg ticket time' ?></span>
        </div>
        <div class="case-study__stat">
            <span class="case-study__stat-num">1<span>/8</span></span>
            <span class="case-study__stat-lbl"><?= $lang === 'fr' ? 'Coût vs ETP évité' : 'Cost vs avoided hire' ?></span>
        </div>
        <div class="case-study__stat">
            <span class="case-study__stat-num">6<span> sem.</span></span>
            <span class="case-study__stat-lbl"><?= $lang === 'fr' ? 'Du brief à la prod' : 'Brief to production' ?></span>
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
        <?php if ($index === 3): ?>
        <!-- Architecture diagram — after chapter 04 -->
        <div class="case-study__diagram">
            <p class="case-study__diagram-lbl"><?= $lang === 'fr' ? 'Architecture — classifieur + routeur' : 'Architecture — classifier + router' ?></p>
            <svg viewBox="0 0 800 220" xmlns="http://www.w3.org/2000/svg" role="img"
                 aria-label="<?= $lang === 'fr' ? 'Diagramme architecture triage' : 'Triage architecture diagram' ?>">
                <defs>
                    <marker id="arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="7" markerHeight="7" orient="auto">
                        <path d="M0,0 L10,5 L0,10 z" fill="currentColor"/>
                    </marker>
                </defs>
                <style>
                    .dg-box { fill: var(--bg, #fcfaf6); stroke: var(--text, #111110); stroke-width: 1; }
                    .dg-box-soft { fill: var(--bg-soft, #f3eee4); stroke: var(--text-3, #9a9895); stroke-width: 1; stroke-dasharray: 3 3; }
                    .dg-t { font-family: -apple-system, system-ui, sans-serif; font-size: 12px; fill: var(--text, #111110); }
                    .dg-t-mono { font-family: ui-monospace, Menlo, monospace; font-size: 9.5px; fill: var(--text-3, #9a9895); letter-spacing: 0.04em; text-transform: uppercase; }
                    .dg-l { stroke: var(--text, #111110); stroke-width: 1; fill: none; marker-end: url(#arrow); }
                    .dg-l-soft { stroke: var(--text-3, #9a9895); stroke-width: 1; stroke-dasharray: 4 3; fill: none; marker-end: url(#arrow); }
                    .dg-pct { font-family: Georgia, serif; font-style: italic; font-size: 13px; fill: var(--text-3, #9a9895); }
                </style>
                <!-- Source -->
                <rect class="dg-box" x="20" y="82" width="130" height="56"/>
                <text class="dg-t-mono" x="32" y="100">Source</text>
                <text class="dg-t" x="32" y="120">Helpdesk webhook</text>
                <text class="dg-t" x="32" y="134" style="fill:var(--text-3,#9a9895)">~800 tickets/j</text>
                <line class="dg-l" x1="150" y1="110" x2="205" y2="110"/>
                <!-- Classifieur -->
                <rect class="dg-box" x="215" y="72" width="160" height="76"/>
                <text class="dg-t-mono" x="227" y="90">Étape 1</text>
                <text class="dg-t" x="227" y="110" style="font-weight:500">Classifieur</text>
                <text class="dg-t" x="227" y="126" style="fill:var(--text-3,#9a9895)">Règles + LLM</text>
                <text class="dg-t" x="227" y="140" style="fill:var(--text-3,#9a9895)">Eval continue</text>
                <line class="dg-l" x1="375" y1="110" x2="430" y2="110"/>
                <!-- Routeur -->
                <rect class="dg-box" x="440" y="72" width="130" height="76"/>
                <text class="dg-t-mono" x="452" y="90">Étape 2</text>
                <text class="dg-t" x="452" y="110" style="font-weight:500">Routeur</text>
                <text class="dg-t" x="452" y="126" style="fill:var(--text-3,#9a9895)">3 voies</text>
                <text class="dg-t" x="452" y="140" style="fill:var(--text-3,#9a9895)">+ kill switch</text>
                <!-- 3 sorties -->
                <line class="dg-l" x1="570" y1="95" x2="620" y2="32"/>
                <line class="dg-l" x1="570" y1="110" x2="620" y2="110"/>
                <line class="dg-l" x1="570" y1="125" x2="620" y2="188"/>
                <rect class="dg-box" x="630" y="8" width="150" height="48"/>
                <text class="dg-t" x="642" y="28" style="font-weight:500">Auto-réponse FAQ</text>
                <text class="dg-pct" x="642" y="46">~30%</text>
                <rect class="dg-box" x="630" y="86" width="150" height="48"/>
                <text class="dg-t" x="642" y="106" style="font-weight:500">Suggestion + humain</text>
                <text class="dg-pct" x="642" y="124">~20%</text>
                <rect class="dg-box-soft" x="630" y="164" width="150" height="48"/>
                <text class="dg-t" x="642" y="184" style="font-weight:500">Humain seul</text>
                <text class="dg-pct" x="642" y="202">~50%</text>
                <!-- Boucle logs/eval -->
                <path class="dg-l-soft" d="M 295 148 Q 295 195, 505 195 Q 505 195, 505 148"/>
                <text class="dg-t-mono" x="360" y="212" style="fill:var(--text-3,#9a9895)">Logs · eval continue</text>
            </svg>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <footer class="case-study__disclaimer">
        <?= htmlspecialchars($case['disclaimer']) ?>
    </footer>
</article>
