<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class CaseStudyController extends Controller
{
    public function triage(): void
    {
        $this->renderCase(
            'triage',
            slug: 'triage-support',
            titleFr: 'Trier 800 tickets/jour sans embaucher · Sonia Habibi',
            titleEn: 'Triage 800 tickets/day without hiring · Sonia Habibi',
            descFr:  'Étude de cas sur une architecture IA utile : triage support, classifieur, fallback humain, budget tokens et garde-fous.',
            descEn:  'Case study on a useful AI architecture: support triage, classifier, human fallback, token budget and guardrails.',
            crumbFr: 'Triage support IA',
            crumbEn: 'AI support triage',
        );
    }

    public function amanea(): void
    {
        $this->renderCase(
            'amanea',
            slug: 'amanea-voyages',
            titleFr: 'Amanéa Voyages — site sur mesure PHP MVC · Sonia Habibi',
            titleEn: 'Amanéa Voyages — bespoke PHP MVC website · Sonia Habibi',
            descFr:  'Étude de cas : site bilingue FR/EN pour une agence de voyages, avec backoffice, espace client et gestion des réservations, sans CMS.',
            descEn:  'Case study: bilingual FR/EN website for a travel agency, with back-office, client portal and booking management, no CMS.',
            crumbFr: 'Amanéa Voyages',
            crumbEn: 'Amanéa Voyages',
        );
    }

    private function renderCase(
        string $view,
        string $slug,
        string $titleFr,
        string $titleEn,
        string $descFr,
        string $descEn,
        string $crumbFr,
        string $crumbEn,
    ): void {
        $isFr   = ($_SESSION['lang'] ?? 'fr') === 'fr';
        $appUrl = base_url();

        $this->render("case-studies/{$view}", [
            'title'            => $isFr ? $titleFr : $titleEn,
            'metaDesc'         => $isFr ? $descFr  : $descEn,
            'canonical'        => "{$appUrl}/case-studies/{$slug}",
            'breadcrumbSchema' => $this->breadcrumb($appUrl, $isFr, $isFr ? $crumbFr : $crumbEn),
        ]);
    }

    private function breadcrumb(string $appUrl, bool $isFr, string $pageName): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home',    'item' => "{$appUrl}/"],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $isFr ? 'Projets' : 'Projects', 'item' => "{$appUrl}/projets"],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $pageName],
            ],
        ];
    }
}
