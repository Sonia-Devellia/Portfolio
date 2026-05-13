<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class ProjectController extends Controller
{
    public function index(): void
    {
        $projects = require ROOT_PATH . '/app/Data/projects.php';
        $isFr     = ($_SESSION['lang'] ?? 'fr') === 'fr';

        $this->render('projects/index', [
            'realisations' => array_values(array_filter($projects, static fn(array $p): bool => $p['kind'] === 'realisation')),
            'casestudies'  => array_values(array_filter($projects, static fn(array $p): bool => $p['kind'] === 'casestudy')),
            'title'        => ($isFr ? 'Travaux' : 'Work') . ' · Sonia Habibi',
            'metaDesc'     => $isFr
                ? 'Deux livraisons, cinq cas d\'études. Le code des projets personnels est public.'
                : 'Two deliveries, five case studies. Personal project code is public.',
            'canonical'    => base_url() . '/projets',
        ]);
    }
}
