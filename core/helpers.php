<?php

declare(strict_types=1);

/**
 * Helpers globaux — disponibles dans toutes les vues et controllers.
 */

if (!function_exists('e')) {
    /**
     * Échappe une chaîne pour insertion sécurisée dans du HTML.
     * Toujours préférer e() à htmlspecialchars() dans les vues.
     */
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Retourne le token CSRF de la session, en l'initialisant au besoin.
     * Toujours injecter via un input hidden, puis vérifier côté POST avec hash_equals().
     */
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Affiche l'input hidden CSRF prêt à coller dans un <form>.
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_check')) {
    /**
     * Vérifie le token CSRF d'une requête POST (timing-safe).
     */
    function csrf_check(): bool
    {
        $sent     = $_POST['csrf_token']    ?? '';
        $expected = $_SESSION['csrf_token'] ?? '';
        return $sent !== '' && $expected !== '' && hash_equals($expected, $sent);
    }
}

if (!function_exists('base_url')) {
    /**
     * URL de base de l'application, sans slash final.
     */
    function base_url(): string
    {
        return rtrim($_ENV['APP_URL'] ?? '', '/');
    }
}
