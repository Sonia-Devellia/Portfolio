<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class GeoController extends Controller
{
    /** @var array<string, array{label:string, region:string, country:string, lang_en:string}> */
    private const CITIES = [
        // ── Grand Ouest — Bretagne ─────────────────────────────────────────
        'rennes'        => ['label' => 'Rennes',        'region' => 'Bretagne',             'country' => 'France',      'lang_en' => 'Rennes'],
        'nantes'        => ['label' => 'Nantes',        'region' => 'Pays de la Loire',     'country' => 'France',      'lang_en' => 'Nantes'],
        'vannes'        => ['label' => 'Vannes',        'region' => 'Bretagne',             'country' => 'France',      'lang_en' => 'Vannes'],
        'brest'         => ['label' => 'Brest',         'region' => 'Bretagne',             'country' => 'France',      'lang_en' => 'Brest'],
        'quimper'       => ['label' => 'Quimper',       'region' => 'Bretagne',             'country' => 'France',      'lang_en' => 'Quimper'],
        'lorient'       => ['label' => 'Lorient',       'region' => 'Bretagne',             'country' => 'France',      'lang_en' => 'Lorient'],
        'saint-brieuc'  => ['label' => 'Saint-Brieuc',  'region' => 'Bretagne',             'country' => 'France',      'lang_en' => 'Saint-Brieuc'],
        'saint-malo'    => ['label' => 'Saint-Malo',    'region' => 'Bretagne',             'country' => 'France',      'lang_en' => 'Saint-Malo'],

        // ── Grand Ouest — Pays de la Loire ─────────────────────────────────
        'angers'        => ['label' => 'Angers',        'region' => 'Pays de la Loire',     'country' => 'France',      'lang_en' => 'Angers'],
        'le-mans'       => ['label' => 'Le Mans',       'region' => 'Pays de la Loire',     'country' => 'France',      'lang_en' => 'Le Mans'],

        // ── Paris ──────────────────────────────────────────────────────────
        'paris'         => ['label' => 'Paris',         'region' => 'Île-de-France',        'country' => 'France',      'lang_en' => 'Paris'],

        // ── Sud-Ouest ──────────────────────────────────────────────────────
        'bordeaux'      => ['label' => 'Bordeaux',      'region' => 'Nouvelle-Aquitaine',   'country' => 'France',      'lang_en' => 'Bordeaux'],
        'toulouse'      => ['label' => 'Toulouse',      'region' => 'Occitanie',            'country' => 'France',      'lang_en' => 'Toulouse'],
        'bayonne'       => ['label' => 'Bayonne',       'region' => 'Pays Basque',          'country' => 'France',      'lang_en' => 'Bayonne'],
        'biarritz'      => ['label' => 'Biarritz',      'region' => 'Pays Basque',          'country' => 'France',      'lang_en' => 'Biarritz'],

        // ── Frontalier Haute-Savoie (proche Genève) ────────────────────────
        'annecy'        => ['label' => 'Annecy',        'region' => 'Haute-Savoie',         'country' => 'France',      'lang_en' => 'Annecy'],
        'annemasse'     => ['label' => 'Annemasse',     'region' => 'Haute-Savoie',         'country' => 'France',      'lang_en' => 'Annemasse'],
        'thonon-les-bains' => ['label' => 'Thonon-les-Bains', 'region' => 'Haute-Savoie',   'country' => 'France',      'lang_en' => 'Thonon-les-Bains'],

        // ── Luxembourg ─────────────────────────────────────────────────────
        'luxembourg'    => ['label' => 'Luxembourg-Ville', 'region' => 'Grand-Duché de Luxembourg', 'country' => 'Luxembourg', 'lang_en' => 'Luxembourg City'],

        // ── Suisse romande ─────────────────────────────────────────────────
        'geneve'        => ['label' => 'Genève',        'region' => 'Suisse romande',       'country' => 'Suisse',      'lang_en' => 'Geneva'],
        'lausanne'      => ['label' => 'Lausanne',      'region' => 'Suisse romande',       'country' => 'Suisse',      'lang_en' => 'Lausanne'],
    ];

    public function show(string $slug): void
    {
        $city = self::CITIES[$slug] ?? null;
        if (!$city) {
            http_response_code(404);
            (new HomeController())->notFound();
            return;
        }

        $appUrl    = base_url();
        $isFr      = ($_SESSION['lang'] ?? 'fr') === 'fr';
        $cityLabel = $city['label'];
        $cityEn    = $city['lang_en'];

        // Contexte local unique à la ville — évite le thin content
        $contexts = require ROOT_PATH . '/app/Data/cities.php';
        $context  = $contexts[$slug] ?? null;

        $title = $isFr
            ? "Développeuse Freelance PHP Python IA à {$cityLabel} — Sonia Habibi"
            : "Freelance PHP Python AI Developer in {$cityEn} — Sonia Habibi";

        $metaDesc = $isFr
            ? "Développeuse freelance PHP, Python et IA à {$cityLabel}. MVPs, applications web, intégrations LLM. Remote, 600-800 €/j. Réponse sous 24h."
            : "Freelance PHP, Python & AI developer in {$cityEn}. MVPs, web applications, LLM integrations. Remote, €600-800/day. Reply within 24h.";

        $this->render('geo/show', [
            'title'            => $title,
            'metaDesc'         => $metaDesc,
            'canonical'        => "{$appUrl}/dev-freelance/{$slug}",
            'breadcrumbSchema' => $this->breadcrumbSchema($appUrl, $isFr, $cityLabel, $cityEn),
            'extraSchemas'     => [$this->localBusinessSchema($appUrl, $isFr, $city, $slug, $metaDesc, $context)],
            'city'             => $cityLabel,
            'cityEn'           => $cityEn,
            'region'           => $city['region'],
            'country'          => $city['country'],
            'slug'             => $slug,
            'isAbroad'         => $city['country'] !== 'France',
            'context'          => $context,
        ]);
    }

    /** @return list<string> Liste des slugs pour le sitemap. */
    public static function slugs(): array
    {
        return array_keys(self::CITIES);
    }

    private function breadcrumbSchema(string $appUrl, bool $isFr, string $cityLabel, string $cityEn): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home', 'item' => "{$appUrl}/"],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $isFr
                    ? "Développeuse freelance {$cityLabel}"
                    : "Freelance developer {$cityEn}",
                ],
            ],
        ];
    }

    private function localBusinessSchema(string $appUrl, bool $isFr, array $city, string $slug, string $metaDesc, ?array $context): array
    {
        $headline = $context[$isFr ? 'headline_fr' : 'headline_en'] ?? null;

        $schema = [
            '@context' => 'https://schema.org',
            '@type'    => 'ProfessionalService',
            'name'     => $isFr
                ? "Sonia Habibi — Développeuse Freelance à {$city['label']}"
                : "Sonia Habibi — Freelance Developer in {$city['lang_en']}",
            'url'         => "{$appUrl}/dev-freelance/{$slug}",
            'image'       => "{$appUrl}/assets/images/sonia.webp",
            'description' => $headline ?? $metaDesc,
            'provider'    => ['@id' => "{$appUrl}#sonia"],
            'areaServed'  => ['@type' => 'City', 'name' => $city['label']],
            'serviceType' => $isFr
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

        // knowsAbout : varie par ville → différencie chaque page géo aux yeux de Google
        if (!empty($context['sectors'])) {
            $schema['knowsAbout'] = $context['sectors'];
        }

        return $schema;
    }
}
