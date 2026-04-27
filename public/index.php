<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// Démarrer la session
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// Headers de sécurité
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Langue par défaut
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = $_ENV['DEFAULT_LANG'] ?? 'fr';
}

// Router
$router = new Core\Router();
require ROOT_PATH . '/config/routes.php';
$router->dispatch();
