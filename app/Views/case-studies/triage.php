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

    <div class="case-study__body">
        <?php foreach ($case['chapters'] as $index => [$id, $title, $body]): ?>
        <section id="<?= htmlspecialchars($id) ?>" class="case-study__section">
            <span class="case-study__num"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <div>
                <h2><?= htmlspecialchars($title) ?></h2>
                <p><?= htmlspecialchars($body) ?></p>
            </div>
        </section>
        <?php endforeach; ?>
    </div>

    <footer class="case-study__disclaimer">
        <?= htmlspecialchars($case['disclaimer']) ?>
    </footer>
</article>
