<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use App\Models\Project;
use App\Helpers\Logger;

class AdminController extends Controller
{
    /** Limite de tentatives consécutives avant blocage temporaire. */
    private const LOGIN_MAX_ATTEMPTS = 5;

    /** Pénalité de base (en secondes), multipliée jusqu'à 5 fois selon le nombre d'échecs. */
    private const LOGIN_BASE_LOCKOUT = 60;

    private function guard(): void
    {
        if (empty($_SESSION['admin'])) {
            Logger::security('admin_access_denied', [
                'uri' => $_SERVER['REQUEST_URI'] ?? '',
            ]);
            $this->redirect('/admin/login');
        }
    }

    private function verifyCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            Logger::security('csrf_rejected', [
                'uri' => $_SERVER['REQUEST_URI'] ?? '',
            ]);
            $this->redirect('/admin/projets');
        }
    }

    // ─── Auth ─────────────────────────────────────────────────

    public function login(): void
    {
        if (!empty($_SESSION['admin'])) {
            $this->redirect('/admin/projets');
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);

        $this->render('admin/login', [
            'title'  => 'Admin — Connexion',
            'layout' => 'layouts/admin',
            'error'  => $error,
        ]);
    }

    public function loginPost(): void
    {
        // ─── Rate limit ───────────────────────────────────────
        $attempts = $_SESSION['login_attempts'] ?? ['count' => 0, 'until' => 0];

        if ($attempts['count'] >= self::LOGIN_MAX_ATTEMPTS && time() < $attempts['until']) {
            Logger::security('login_rate_limited', [
                'count' => $attempts['count'],
                'wait_s' => max(0, $attempts['until'] - time()),
            ]);
            http_response_code(429);
            $_SESSION['admin_error'] = 'rate_limit';
            $this->redirect('/admin/login');
            return;
        }

        // ─── CSRF ─────────────────────────────────────────────
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            Logger::security('login_csrf_rejected');
            $_SESSION['admin_error'] = 'csrf';
            $this->redirect('/admin/login');
            return;
        }

        // ─── Vérification credentials ─────────────────────────
        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');

        $validUser = hash_equals($_ENV['ADMIN_USER'] ?? '', $user);
        $validPass = password_verify($pass, $_ENV['ADMIN_PASS_HASH'] ?? '');

        if ($validUser && $validPass) {
            unset($_SESSION['login_attempts']);
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            Logger::security('login_success', ['user' => $user]);
            $this->redirect('/admin/projets');
            return;
        }

        // ─── Échec — incrémenter le compteur avec backoff ────
        $newCount  = $attempts['count'] + 1;
        $lockoutMs = self::LOGIN_BASE_LOCKOUT * min(5, $newCount);

        $_SESSION['login_attempts'] = [
            'count' => $newCount,
            'until' => time() + $lockoutMs,
        ];

        Logger::security('login_failed', [
            'attempt' => $newCount,
            'username' => $user, // jamais le password
        ]);

        $_SESSION['admin_error'] = 'credentials';
        $this->redirect('/admin/login');
    }

    public function logout(): void
    {
        Logger::security('logout', [
            'session_was_admin' => !empty($_SESSION['admin']),
        ]);
        unset($_SESSION['admin']);
        session_regenerate_id(true);
        $this->redirect('/admin/login');
    }

    // ─── Dashboard ────────────────────────────────────────────

    public function index(): void
    {
        $this->guard();
        $this->redirect('/admin/projets');
    }

    // ─── Projets ──────────────────────────────────────────────

    public function projects(): void
    {
        $this->guard();
        $projects = Project::getAll();
        $this->render('admin/projects', [
            'projects' => $projects,
            'title'    => 'Admin — Projets',
            'layout'   => 'layouts/admin',
        ]);
    }

    public function newProject(): void
    {
        $this->guard();
        $this->render('admin/project_form', [
            'project' => null,
            'title'   => 'Nouveau projet',
            'layout'  => 'layouts/admin',
        ]);
    }

    public function createProject(): void
    {
        $this->guard();
        $this->verifyCsrf();
        Project::create($this->sanitizeProjectPost());
        $this->redirect('/admin/projets');
    }

    public function editProject(string $id): void
    {
        $this->guard();
        $project = Project::getById((int) $id);
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
        $this->guard();
        $this->verifyCsrf();
        Project::update((int) $id, $this->sanitizeProjectPost());
        $this->redirect('/admin/projets');
    }

    public function deleteProject(string $id): void
    {
        $this->guard();
        $this->verifyCsrf();
        Project::delete((int) $id);
        $this->redirect('/admin/projets');
    }

    // ─── Helpers ──────────────────────────────────────────────

    private function sanitizeProjectPost(): array
    {
        return [
            'title_fr'    => trim($_POST['title_fr']   ?? ''),
            'title_en'    => trim($_POST['title_en']   ?? ''),
            'desc_fr'     => trim($_POST['desc_fr']    ?? ''),
            'desc_en'     => trim($_POST['desc_en']    ?? ''),
            'slug'        => trim($_POST['slug']       ?? ''),
            'tags'        => trim($_POST['tags']       ?? ''),
            'github_url'  => trim($_POST['github_url'] ?? ''),
            'demo_url'    => trim($_POST['demo_url']   ?? ''),
            'thumbnail'   => trim($_POST['thumbnail']  ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_wip'      => isset($_POST['is_wip'])       ? 1 : 0,
            'sort_order'  => (int) ($_POST['sort_order']   ?? 0),
        ];
    }
}
