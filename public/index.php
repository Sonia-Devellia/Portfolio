<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';
require ROOT_PATH . '/core/helpers.php';

// ─── Variables d'environnement ──────────────────────────────────────────────
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$isProd = ($_ENV['APP_ENV'] ?? 'local') === 'production';

// ─── Affichage des erreurs PHP ──────────────────────────────────────────────
// En prod : tout masqué côté navigateur, tout loggé côté disque.
// En local : tout visible.
if ($isProd) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');

    $logDir = ROOT_PATH . '/storage/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    ini_set('error_log', $logDir . '/php-errors.log');
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// ─── Session ────────────────────────────────────────────────────────────────
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $isProd,           // HTTPS uniquement en prod
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// ─── Headers de sécurité ────────────────────────────────────────────────────
// Anti-clickjacking
header('X-Frame-Options: SAMEORIGIN');

// MIME sniffing désactivé
header('X-Content-Type-Options: nosniff');

// Referrer minimal
header('Referrer-Policy: strict-origin-when-cross-origin');

// HSTS — uniquement en prod, après vérification que HTTPS est OK
if ($isProd) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// Permissions — couper tout ce qu'on n'utilise pas
header("Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=(), accelerometer=(), gyroscope=()");

// CSP — strict, compatible Google Fonts + JSON-LD inline
header("Content-Security-Policy: "
    . "default-src 'self'; "
    . "script-src 'self'; "
    . "style-src 'self' https://fonts.googleapis.com; "
    . "font-src 'self' https://fonts.gstatic.com; "
    . "img-src 'self' data: https:; "
    . "connect-src 'self'; "
    . "frame-ancestors 'self'; "
    . "form-action 'self'; "
    . "base-uri 'self'"
);

// ─── Langue par défaut ──────────────────────────────────────────────────────
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = $_ENV['DEFAULT_LANG'] ?? 'fr';
}

// ─── Router ─────────────────────────────────────────────────────────────────
$router = new Core\Router();
require ROOT_PATH . '/config/routes.php';
$router->dispatch();
