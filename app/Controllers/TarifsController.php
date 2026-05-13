<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class TarifsController extends Controller
{
    public function index(): void
    {
        $appUrl = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang   = $_SESSION['lang'] ?? 'fr';

        $title   = $lang === 'fr'
            ? 'Tarifs & Modes de collaboration — Freelance, Remote, Mi-temps · Sonia Habibi'
            : 'Rates & Collaboration modes — Freelance, Remote, Part-time · Sonia Habibi';
        $metaDesc = $lang === 'fr'
            ? 'Développeuse PHP Python IA — TJM 600-800 €/j en freelance, disponible en CDI remote ou CDI mi-temps. Trois façons de travailler ensemble selon votre structure.'
            : 'PHP Python AI developer — Day rate €600-800 as freelance, available for full remote CDI or part-time CDI. Three ways to work together depending on your structure.';

        $faqSchema = $this->tarifsFaqSchema($lang);

        $priceSchema = [
            '@context' => 'https://schema.org',
            '@type'    => 'PriceSpecification',
            'name'     => $lang === 'fr'
                ? 'TJM développeuse freelance PHP Python IA — Sonia Habibi'
                : 'Freelance PHP Python AI developer day rate — Sonia Habibi',
            'priceCurrency' => 'EUR',
            'minPrice'      => 600,
            'maxPrice'      => 800,
            'unitText'      => $lang === 'fr' ? 'par jour' : 'per day',
            'eligibleTransactionVolume' => [
                '@type'       => 'PriceSpecification',
                'minPrice'    => 6000,
                'description' => $lang === 'fr'
                    ? 'Forfait projet — minimum 2 semaines de travail'
                    : 'Project quote — minimum 2 weeks of work',
            ],
        ];

        $this->render('home/tarifs', [
            'title'           => $title,
            'metaDesc'        => $metaDesc,
            'canonical'       => $appUrl . '/tarifs',
            'faqSchema'       => $faqSchema,
            'extraSchemas'    => [$priceSchema],
        ]);
    }

    private function tarifsFaqSchema(string $lang): array
    {
        $items = $lang === 'fr'
            ? [
                ['Quand choisir le freelance plutôt qu\'embaucher ?',
                 'Le freelance est idéal pour un besoin ponctuel, un MVP à lancer, une mission technique délimitée ou un renfort sans engagement long terme. Le CDI s\'impose quand le besoin est permanent et que l\'intégration dans l\'équipe produit est stratégique.'],
                ['Qu\'est-ce qu\'un CDI remote full-time concrètement ?',
                 'Un contrat salarié classique (35h/semaine) avec télétravail 100 %. Pas de déménagement, pas de présentiel imposé. Disponibilité complète dans votre stack, votre outillage, vos rituels d\'équipe.'],
                ['Qu\'est-ce qu\'un CDI mi-temps (50 %) ?',
                 'Un contrat salarié à 17h30 par semaine. Idéal pour les startups early-stage qui ont besoin d\'une vraie expertise tech sans embaucher à temps plein. Je consacre l\'autre moitié à mes autres engagements freelance.'],
                ['Quelle est la différence légale entre prestataire et salarié ?',
                 'En tant que prestataire (freelance), je suis indépendante — vous êtes client, pas employeur. En CDI, vous devenez employeur avec les obligations afférentes (charges sociales, mutuelle, congés). Le choix dépend de votre besoin en termes d\'engagement et de dépendance économique.'],
                ['Le TJM est-il négociable ?',
                 'Le TJM varie entre 600 et 800 €/j selon la durée de mission (plus long = plus prévisible), la complexité technique et la présence ou non d\'IA dans le scope. Forfait possible à partir de 2 semaines de travail, sans surprise sur la facture finale.'],
                ['Quelles sont vos prétentions salariales en CDI ?',
                 'Disponibles sur demande après un premier échange pour cadrer le poste. Je ne communique pas de fourchette sans avoir compris le périmètre réel, les avantages et la situation de l\'entreprise.'],
            ]
            : [
                ['When should I choose freelance over hiring?',
                 'Freelance is ideal for a defined technical need, an MVP to launch, a scoped mission or a short-term reinforcement without long-term commitment. A CDI makes sense when the need is permanent and technical integration into your product team is strategic.'],
                ['What does a full-time remote CDI look like in practice?',
                 'A standard employment contract (35h/week) with 100% remote work. No relocation required, no mandatory on-site presence. Full availability within your stack, your tooling and your team rituals.'],
                ['What is a part-time CDI (50%)?',
                 'An employment contract at 17h30 per week. Ideal for early-stage startups that need real technical expertise without hiring full-time. I dedicate the other half of my week to my other freelance engagements.'],
                ['What is the legal difference between a contractor and an employee?',
                 'As a freelance contractor, I am self-employed — you are a client, not an employer. Under a CDI, you become an employer with the associated obligations (social charges, health insurance, paid leave). The choice depends on your need in terms of commitment and economic dependency.'],
                ['Is the day rate negotiable?',
                 'The day rate ranges from €600 to €800/day depending on mission length (longer = more predictable), technical complexity and whether AI is in scope. Fixed-price quotes available from 2 weeks of work, no surprise on the final invoice.'],
                ['What are your salary expectations for a CDI?',
                 'Available on request after an initial conversation to frame the role. I do not communicate a salary range without first understanding the real scope, benefits and company situation.'],
            ];

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => array_map(static fn(array $item): array => [
                '@type'          => 'Question',
                'name'           => $item[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item[1]],
            ], $items),
        ];
    }
}
