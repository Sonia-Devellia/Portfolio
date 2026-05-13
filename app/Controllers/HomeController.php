<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $projects = require ROOT_PATH . '/app/Data/projects.php';
        $stack    = require ROOT_PATH . '/app/Data/stack.php';
        $featured = array_values(array_filter($projects, static fn(array $p): bool => $p['featured'] === true));

        $lang     = $_SESSION['lang'] ?? 'fr';
        $isFr     = $lang === 'fr';
        $appUrl   = base_url();
        $metaDesc = $isFr
            ? 'Développeuse freelance PHP, Python et IA — MVPs, applications métier, intégrations LLM. Pour startups et PME en France, Suisse et Belgique. Remote, 600-800 €/j.'
            : 'Freelance PHP, Python & AI developer — MVPs, business apps, LLM integrations. For startups & SMBs in France, Switzerland and Belgium. Remote, €600-800/day.';

        $this->render('home/index', [
            'projects'     => $featured,
            'stack'        => $stack,
            'title'        => $isFr
                ? 'Développeuse Freelance PHP Python IA — Sonia Habibi'
                : 'Freelance PHP Python AI Developer — Sonia Habibi',
            'metaDesc'     => $metaDesc,
            'canonical'    => $appUrl . '/',
            'faqSchema'    => $this->faqSchema($isFr),
            'extraSchemas' => [$this->professionalServiceSchema($isFr, $appUrl, $metaDesc)],
        ]);
    }

    public function notFound(): void
    {
        $this->render('home/404', ['title' => '404']);
    }

    // ─── Schemas ──────────────────────────────────────────────

    private function faqSchema(bool $isFr): array
    {
        $items = $isFr ? [
            ['Quel est votre TJM ?',
             'Entre 600€ et 800€/jour selon la nature du projet. Devis forfaitaire pour les missions inférieures à 6 semaines, régie au-delà. Premier appel gratuit pour cadrer.'],
            ['À partir de quel scope vaut-il la peine de m’écrire ?',
             'À partir de deux semaines pleines. En dessous, je vous orienterai vers une autre ressource : ce n’est pas la bonne échelle pour un freelance senior, et vous serez mieux servi.'],
            ['Qu’est-ce que vous ne faites pas ?',
             'Pas de design from scratch, pas de mobile natif iOS/Android, pas de wrapper d’API LLM en façade marketing.'],
            ['Travaillez-vous avec des agences ?',
             'Oui, en sous-traitance technique pour des agences de design ou de stratégie qui ont besoin d’un dev senior fiable. Conditions sur demande, NDA standard.'],
            ['Quels délais réalistes ?',
             'Première fenêtre disponible communiquée sous 48h après votre brief. Démarrage typique sous 2 à 4 semaines. Pour un MVP web complet, comptez 6 à 10 semaines de production.'],
            ['Quelle est la différence entre un développeur freelance et une agence web ?',
             'Un freelance senior offre un interlocuteur unique, une décision technique directe et un TJM de 600-800 €/j — sans les coûts de structure d\'une agence (chef de projet, commercial, frais généraux). La contrepartie : pas de disponibilité permanente pour les urgences.'],
            ['Acceptez-vous les projets de clients en Suisse ?',
             'Oui. Je travaille 100 % en remote avec des clients en France, en Suisse romande (Genève, Lausanne) et en Belgique. Facturation en euros, contrat de prestation française ou suisse selon la préférence.'],
            ['Qu\'est-ce qu\'une intégration LLM concrètement ?',
             'L\'intégration d\'un LLM (Claude, GPT-4) dans une application web connecte l\'API du modèle à votre back-end PHP ou Python pour automatiser une tâche coûteuse : classification de tickets, extraction de données, réponse automatique. Je livre avec un fallback déterministe, un budget tokens et une observabilité complète.'],
        ] : [
            ['What is your day rate?',
             'Between €600 and €800/day depending on the project. Fixed quote for missions under 6 weeks, day rate beyond that. First scoping call is free.'],
            ['What minimum scope is worth contacting you for?',
             'Two full weeks minimum. Below that, I will point you to another resource: it is not the right scale for a senior freelance profile, and you will be better served.'],
            ['What do you not do?',
             'No design from scratch, no native iOS/Android, no LLM API wrapper used as a marketing facade.'],
            ['Do you work with agencies?',
             'Yes, as a technical subcontractor for design or strategy agencies that need a reliable senior developer. Terms on request, standard NDA.'],
            ['What are realistic timelines?',
             'First available slot communicated within 48h after your brief. Typical start within 2 to 4 weeks. For a complete web MVP, expect 6 to 10 production weeks.'],
            ['What is the difference between a freelance developer and a web agency?',
             'A senior freelance offers a single point of contact, direct technical decisions and a day rate of €600-800 — without agency overhead (project manager, sales, fixed costs). The trade-off: no permanent on-call availability for emergencies.'],
            ['Do you work with clients in Switzerland?',
             'Yes. I work 100% remotely with clients in France, French-speaking Switzerland (Geneva, Lausanne) and Belgium. Invoicing in euros, French or Swiss services agreement as preferred.'],
            ['What does an LLM integration actually mean?',
             'Integrating an LLM (Claude, GPT-4) into a web application connects the model\'s API to your PHP or Python back end to automate a costly task: ticket classification, document extraction, automated response. Delivered with a deterministic fallback, token budget and full observability.'],
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

    private function professionalServiceSchema(bool $isFr, string $appUrl, string $description): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type'    => 'ProfessionalService',
            '@id'      => $appUrl . '#service',
            'name'     => $isFr
                ? 'Sonia Habibi — Développement web freelance'
                : 'Sonia Habibi — Freelance Web Development',
            'description' => $description,
            'provider'    => ['@id' => $appUrl . '#sonia'],
            'areaServed'  => ['FR', 'EU', 'Worldwide remote'],
            'priceRange'  => '€€€',
            'serviceType' => [
                $isFr ? 'Développement web full-stack' : 'Full-stack web development',
                'Intégration API IA (Claude, OpenAI)',
                'MVP & prototypes',
                'Audit et sécurisation PHP',
            ],
            'url'   => $appUrl,
            'image' => $appUrl . '/assets/images/sonia.webp',
        ];
    }
}
