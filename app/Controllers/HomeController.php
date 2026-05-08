<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Project;

class HomeController extends Controller
{
    public function index(): void
    {
        $projects  = Project::getFeatured(4);
        $appUrl    = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang      = $_SESSION['lang'] ?? 'fr';
        $metaDesc  = $lang === 'fr'
            ? 'Développeuse freelance full-stack — PHP, Python, intégrations IA. Sites, applications et MVP livrés en remote, code propre et sécurisé.'
            : 'Freelance full-stack developer — PHP, Python, AI integrations. Websites, apps and MVPs delivered remotely, clean and secure code.';
        $faqSchema = $this->homeFaqSchema($lang);

        $this->render('home/index', [
            'projects'   => $projects,
            'title'      => $lang === 'fr'
                ? 'Développeuse full-stack PHP Python IA · Sonia Habibi'
                : 'Full-Stack Developer PHP Python AI · Sonia Habibi',
            'metaDesc'   => $metaDesc,
            'canonical'  => $appUrl . '/',
            'faqSchema'  => $faqSchema,
        ]);
    }

    public function notFound(): void
    {
        $this->render('home/404', [
            'title' => '404',
        ]);
    }

    private function homeFaqSchema(string $lang): array
    {
        $items = $lang === 'fr'
            ? [
                ['Quel est votre TJM ?', 'Entre 600€ et 800€/jour selon la nature du projet. Devis forfaitaire pour les missions inférieures à 6 semaines, régie au-delà. Premier appel gratuit pour cadrer.'],
                ['À partir de quel scope vaut-il la peine de m’écrire ?', 'À partir de deux semaines pleines. En dessous, je vous orienterai vers une autre ressource : ce n’est pas la bonne échelle pour un freelance senior, et vous serez mieux servi.'],
                ['Qu’est-ce que vous ne faites pas ?', 'Pas de design from scratch, pas de mobile natif iOS/Android, pas de wrapper d’API LLM en façade marketing.'],
                ['Travaillez-vous avec des agences ?', 'Oui, en sous-traitance technique pour des agences de design ou de stratégie qui ont besoin d’un dev senior fiable. Conditions sur demande, NDA standard.'],
                ['Quels délais réalistes ?', 'Première fenêtre disponible communiquée sous 48h après votre brief. Démarrage typique sous 2 à 4 semaines. Pour un MVP web complet, comptez 6 à 10 semaines de production.'],
            ]
            : [
                ['What is your day rate?', 'Between €600 and €800/day depending on the project. Fixed quote for missions under 6 weeks, day rate beyond that. First scoping call is free.'],
                ['What minimum scope is worth contacting you for?', 'Two full weeks minimum. Below that, I will point you to another resource: it is not the right scale for a senior freelance profile, and you will be better served.'],
                ['What do you not do?', 'No design from scratch, no native iOS/Android, no LLM API wrapper used as a marketing facade.'],
                ['Do you work with agencies?', 'Yes, as a technical subcontractor for design or strategy agencies that need a reliable senior developer. Terms on request, standard NDA.'],
                ['What are realistic timelines?', 'First available slot communicated within 48h after your brief. Typical start within 2 to 4 weeks. For a complete web MVP, expect 6 to 10 production weeks.'],
            ];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(static fn(array $item): array => [
                '@type' => 'Question',
                'name' => $item[0],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item[1],
                ],
            ], $items),
        ];
    }
}
