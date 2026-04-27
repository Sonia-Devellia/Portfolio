<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Project;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['admin'])) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void
    {
        $this->redirect('/admin/projets');
    }

    public function projects(): void
    {
        $projects = Project::getAll();
        $this->render('admin/projects', [
            'projects' => $projects,
            'title'    => 'Admin — Projets',
            'layout'   => 'layouts/admin',
        ]);
    }

    public function newProject(): void
    {
        $this->render('admin/project_form', [
            'project' => null,
            'title'   => 'Nouveau projet',
            'layout'  => 'layouts/admin',
        ]);
    }

    public function createProject(): void
    {
        $data = $this->sanitizeProjectPost();
        Project::create($data);
        $this->redirect('/admin/projets');
    }

    public function editProject(string $id): void
    {
        $project = Project::getById((int)$id);
        if (!$project) {
            $this->redirect('/admin/projets');
        }

        $this->render('admin/project_form', [
            'project' => $project,
            'title'   => 'Modifier projet',
            'layout'  => 'layouts/admin',
        ]);
    }

    public function updateProject(string $id): void
    {
        $data = $this->sanitizeProjectPost();
        Project::update((int)$id, $data);
        $this->redirect('/admin/projets');
    }

    private function sanitizeProjectPost(): array
    {
        return [
            'title_fr'       => trim($_POST['title_fr']       ?? ''),
            'title_en'       => trim($_POST['title_en']       ?? ''),
            'desc_fr'        => trim($_POST['desc_fr']         ?? ''),
            'desc_en'        => trim($_POST['desc_en']         ?? ''),
            'slug'           => trim($_POST['slug']            ?? ''),
            'tags'           => trim($_POST['tags']            ?? ''),
            'github_url'     => trim($_POST['github_url']      ?? ''),
            'demo_url'       => trim($_POST['demo_url']        ?? ''),
            'thumbnail'      => trim($_POST['thumbnail']       ?? ''),
            'is_featured'    => isset($_POST['is_featured']) ? 1 : 0,
            'is_wip'         => isset($_POST['is_wip'])       ? 1 : 0,
            'sort_order'     => (int)($_POST['sort_order']     ?? 0),
        ];
    }
}
