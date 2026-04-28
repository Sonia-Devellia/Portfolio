<?php

declare(strict_types=1);

/**
 * Génère une URL interne en préfixant le chemin de base extrait de APP_URL.
 *
 * Dev  : APP_URL=http://localhost:8888/portfolio/public → base="/portfolio/public"
 * Prod : APP_URL=https://sonia-habibi.dev              → base=""
 *
 * Exemples :
 *   url('/projets')          → /portfolio/public/projets
 *   url('/')                 → /portfolio/public
 *   url('/#services')        → /portfolio/public/#services
 *   url('/assets/css/main.css') → /portfolio/public/assets/css/main.css
 */
function url(string $path): string
{
    static $base = null;

    if ($base === null) {
        $parsed = parse_url($_ENV['APP_URL'] ?? '', PHP_URL_PATH);
        $base   = rtrim($parsed ?? '', '/');
    }

    if ($path === '/') {
        return $base ?: '/';
    }

    // Ancre sur la racine : /#services → /portfolio/public/#services
    if (str_starts_with($path, '/#')) {
        return $base . $path;
    }

    return $base . '/' . ltrim($path, '/');
}
