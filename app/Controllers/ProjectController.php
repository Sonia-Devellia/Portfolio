<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(): void
    {
        $projects = Project::getAll();

        $this->render('projects/index', [
            'projects' => $projects,
            'title'    => 'Projets — Sonia Habibi',
        ]);
    }

    public function show(string $slug): void
    {
        $project = Project::getBySlug($slug);

        if (!$project) {
            http_response_code(404);
            $this->render('home/404', ['title' => '404']);
            return;
        }

        $this->render('projects/show', [
            'project' => $project,
            'title'   => $project['title_' . ($_SESSION['lang'] ?? 'fr')] . ' — Sonia Habibi',
        ]);
    }
}
