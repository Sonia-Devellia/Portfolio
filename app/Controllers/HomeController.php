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

        $this->render('home/index', [
            'projects'  => $projects,
            'title'     => 'Sonia Habibi — Dev Full-Stack · PHP · Python · IA',
            'metaDesc'  => $metaDesc,
            'canonical' => $appUrl . '/',
        ]);
    }

    public function notFound(): void
    {
        $this->render('home/404', [
            'title' => '404',
        ]);
    }
}
