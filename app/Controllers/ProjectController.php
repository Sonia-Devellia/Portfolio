<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class ProjectController extends Controller
{
    public function index(): void
    {
        $projects  = require ROOT_PATH . '/app/Data/projects.php';
        $appUrl    = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang      = $_SESSION['lang'] ?? 'fr';
        $metaDesc  = $lang === 'fr'
            ? 'Deux livraisons, cinq cas d\'études. Le code des projets personnels est public.'
            : 'Two deliveries, five case studies. Personal project code is public.';

        $realisations = array_filter($projects, fn($p) => $p['kind'] === 'realisation');
        $casestudies  = array_filter($projects, fn($p) => $p['kind'] === 'casestudy');

        $this->render('projects/index', [
            'realisations' => array_values($realisations),
            'casestudies'  => array_values($casestudies),
            'title'        => ($lang === 'fr' ? 'Travaux' : 'Work') . ' · Sonia Habibi',
            'metaDesc'     => $metaDesc,
            'canonical'    => $appUrl . '/projets',
        ]);
    }
}
