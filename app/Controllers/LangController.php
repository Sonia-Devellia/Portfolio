<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class LangController extends Controller
{
    private const ALLOWED = ['fr', 'en'];

    public function switch(string $code): void
    {
        if (in_array($code, self::ALLOWED, true)) {
            $_SESSION['lang'] = $code;
        }

        // Redirection sécurisée : on n'accepte que les chemins relatifs internes,
        // jamais le Referer brut (qui peut être attaquant-contrôlé → open redirect).
        $target = $this->safeRedirectPath($_SERVER['HTTP_REFERER'] ?? '/');

        header('Location: ' . base_url() . $target);
        exit;
    }

    /**
     * Extrait un chemin interne sûr depuis un Referer.
     * Retourne '/' si le referer est externe, vide ou suspect.
     */
    private function safeRedirectPath(string $referer): string
    {
        if ($referer === '') {
            return '/';
        }

        $parts = parse_url($referer);
        $host  = $parts['host'] ?? '';

        // Referer interne ou même host que APP_URL
        $appHost = parse_url($_ENV['APP_URL'] ?? '', PHP_URL_HOST) ?? '';
        if ($host !== '' && $host !== $appHost && $host !== ($_SERVER['HTTP_HOST'] ?? '')) {
            return '/';
        }

        $path = $parts['path'] ?? '/';

        // On enlève le base path si on est en sous-dossier MAMP
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($basePath && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        // Garde-fou : path doit commencer par '/' et ne contenir aucun caractère exotique
        if ($path === '' || $path[0] !== '/' || preg_match('#[\r\n\0]#', $path)) {
            return '/';
        }

        return $path;
    }
}
