<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class CaseStudyController extends Controller
{
    public function triage(): void
    {
        $appUrl   = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang     = $_SESSION['lang'] ?? 'fr';
        $title    = $lang === 'fr'
            ? 'Trier 800 tickets/jour sans embaucher · Sonia Habibi'
            : 'Triage 800 tickets/day without hiring · Sonia Habibi';
        $metaDesc = $lang === 'fr'
            ? 'Étude de cas sur une architecture IA utile : triage support, classifieur, fallback humain, budget tokens et garde-fous.'
            : 'Case study on a useful AI architecture: support triage, classifier, human fallback, token budget and guardrails.';

        $this->render('case-studies/triage', [
            'title'            => $title,
            'metaDesc'         => $metaDesc,
            'canonical'        => $appUrl . '/case-studies/triage-support',
            'breadcrumbSchema' => $this->breadcrumb($appUrl, $lang, $lang === 'fr' ? 'Triage support IA' : 'AI support triage'),
        ]);
    }

    public function amanea(): void
    {
        $appUrl   = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang     = $_SESSION['lang'] ?? 'fr';
        $title    = $lang === 'fr'
            ? 'Amanéa Voyages — site sur mesure PHP MVC · Sonia Habibi'
            : 'Amanéa Voyages — bespoke PHP MVC website · Sonia Habibi';
        $metaDesc = $lang === 'fr'
            ? 'Étude de cas : site bilingue FR/EN pour une agence de voyages, avec backoffice, espace client et gestion des réservations, sans CMS.'
            : 'Case study: bilingual FR/EN website for a travel agency, with back-office, client portal and booking management, no CMS.';

        $this->render('case-studies/amanea', [
            'title'            => $title,
            'metaDesc'         => $metaDesc,
            'canonical'        => $appUrl . '/case-studies/amanea-voyages',
            'breadcrumbSchema' => $this->breadcrumb($appUrl, $lang, 'Amanéa Voyages'),
        ]);
    }

    private function breadcrumb(string $appUrl, string $lang, string $pageName): array
    {
        $isFr = $lang === 'fr';
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home',    'item' => $appUrl . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $isFr ? 'Projets' : 'Projects', 'item' => $appUrl . '/projets'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $pageName],
            ],
        ];
    }
}
