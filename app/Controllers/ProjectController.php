<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(): void
    {
        $projects  = Project::getAll();
        $appUrl    = rtrim($_ENV['APP_URL'] ?? 'https://sonia-habibi.dev', '/');
        $lang      = $_SESSION['lang'] ?? 'fr';
        $metaDesc  = $lang === 'fr'
            ? 'Découvrez mes projets web full-stack — PHP, Python, intégration IA. Applications sur mesure, MVP et automatisations livrés en remote.'
            : 'Browse my full-stack web projects — PHP, Python, AI integration. Custom apps, MVPs and automations delivered remotely.';

        $this->render('projects/index', [
            'projects'  => $projects,
            'title'     => ($lang === 'fr' ? 'Projets' : 'Projects') . ' — Sonia Habibi',
            'metaDesc'  => $metaDesc,
            'canonical' => $appUrl . '/projets',
        ]);
    }

}
