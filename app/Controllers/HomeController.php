<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Project;

class HomeController extends Controller
{
    public function index(): void
    {
        $projects = Project::getFeatured(4);

        $this->render('home/index', [
            'projects' => $projects,
            'title'    => 'Sonia Habibi — Dev Full-Stack',
        ]);
    }

    public function notFound(): void
    {
        $this->render('home/404', [
            'title' => '404',
        ]);
    }
}
