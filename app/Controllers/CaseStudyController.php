<?php

namespace App\Controllers;

use Core\Controller;

class CaseStudyController extends Controller
{
    public function triage(): void
    {
        $appUrl = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang = $_SESSION['lang'] ?? 'fr';
        $title = $lang === 'fr'
            ? 'Trier 800 tickets/jour sans embaucher · Sonia Habibi'
            : 'Triage 800 tickets/day without hiring · Sonia Habibi';
        $metaDesc = $lang === 'fr'
            ? 'Étude de cas sur une architecture IA utile : triage support, classifieur, fallback humain, budget tokens et garde-fous.'
            : 'Case study on a useful AI architecture: support triage, classifier, human fallback, token budget and guardrails.';

        $this->render('case-studies/triage', [
            'title' => $title,
            'metaDesc' => $metaDesc,
            'canonical' => $appUrl . '/case-studies/triage-support',
        ]);
    }
}
