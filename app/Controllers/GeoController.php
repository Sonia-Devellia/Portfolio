<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class GeoController extends Controller
{
    private const CITIES = [
        'paris'    => [
            'label'   => 'Paris',
            'region'  => 'Île-de-France',
            'country' => 'France',
            'lang_en' => 'Paris',
        ],
        'lyon'     => [
            'label'   => 'Lyon',
            'region'  => 'Auvergne-Rhône-Alpes',
            'country' => 'France',
            'lang_en' => 'Lyon',
        ],
        'nantes'   => [
            'label'   => 'Nantes',
            'region'  => 'Pays de la Loire',
            'country' => 'France',
            'lang_en' => 'Nantes',
        ],
        'bordeaux' => [
            'label'   => 'Bordeaux',
            'region'  => 'Nouvelle-Aquitaine',
            'country' => 'France',
            'lang_en' => 'Bordeaux',
        ],
        'vannes'   => [
            'label'   => 'Vannes',
            'region'  => 'Bretagne',
            'country' => 'France',
            'lang_en' => 'Vannes',
        ],
        'geneve'   => [
            'label'   => 'Genève',
            'region'  => 'Suisse romande',
            'country' => 'Suisse',
            'lang_en' => 'Geneva',
        ],
        'lausanne' => [
            'label'   => 'Lausanne',
            'region'  => 'Suisse romande',
            'country' => 'Suisse',
            'lang_en' => 'Lausanne',
        ],
    ];

    public function show(string $slug): void
    {
        $city = self::CITIES[$slug] ?? null;
        if (!$city) {
            http_response_code(404);
            (new HomeController())->notFound();
            return;
        }

        $appUrl = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang   = $_SESSION['lang'] ?? 'fr';

        $cityLabel  = $city['label'];
        $cityEn     = $city['lang_en'];
        $region     = $city['region'];
        $country    = $city['country'];
        $isSuisse   = $country === 'Suisse';

        $title = $lang === 'fr'
            ? "Développeuse Freelance PHP Python IA à {$cityLabel} — Sonia Habibi"
            : "Freelance PHP Python AI Developer in {$cityEn} — Sonia Habibi";

        $metaDesc = $lang === 'fr'
            ? "Développeuse freelance PHP, Python et IA à {$cityLabel}. MVPs, applications web, intégrations LLM. Remote, 600-800 €/j. Réponse sous 24h."
            : "Freelance PHP, Python & AI developer in {$cityEn}. MVPs, web applications, LLM integrations. Remote, €600-800/day. Reply within 24h.";

        $breadcrumbSchema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => ($lang === 'fr' ? 'Accueil' : 'Home'), 'item' => $appUrl . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => ($lang === 'fr' ? "Développeuse freelance {$cityLabel}" : "Freelance developer {$cityEn}")],
            ],
        ];

        $localBusinessSchema = [
            '@context' => 'https://schema.org',
            '@type'    => 'ProfessionalService',
            'name'     => $lang === 'fr'
                ? "Sonia Habibi — Développeuse Freelance à {$cityLabel}"
                : "Sonia Habibi — Freelance Developer in {$cityEn}",
            'url'              => $appUrl . "/dev-freelance/{$slug}",
            'image'            => $appUrl . '/assets/images/sonia.webp',
            'description'      => $metaDesc,
            'provider'         => ['@id' => $appUrl . '#sonia'],
            'areaServed'       => [
                '@type' => 'City',
                'name'  => $cityLabel,
            ],
            'serviceType'      => $lang === 'fr'
                ? ['Développement web full-stack', 'Intégration IA (Claude, OpenAI)', 'MVP & prototypes', 'Audit PHP']
                : ['Full-stack web development', 'AI integration (Claude, OpenAI)', 'MVP & prototypes', 'PHP audit'],
            'availableChannel' => [
                '@type'           => 'ServiceChannel',
                'serviceType'     => 'Remote',
                'serviceLocation' => [
                    '@type' => 'VirtualLocation',
                    'name'  => 'Remote — 100% en ligne',
                ],
            ],
        ];

        $this->render('geo/show', [
            'title'              => $title,
            'metaDesc'           => $metaDesc,
            'canonical'          => $appUrl . "/dev-freelance/{$slug}",
            'breadcrumbSchema'   => $breadcrumbSchema,
            'extraSchemas'       => [$localBusinessSchema],
            'city'               => $cityLabel,
            'cityEn'             => $cityEn,
            'region'             => $region,
            'country'            => $country,
            'slug'               => $slug,
            'isSuisse'           => $isSuisse,
        ]);
    }
}
