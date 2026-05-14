<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';
require ROOT_PATH . '/core/helpers.php';

// ─── Variables d'environnement ──────────────────────────────────────────────
Dotenv\Dotenv::createImmutable(ROOT_PATH)->load();

$isProd = ($_ENV['APP_ENV'] ?? 'local') === 'production';

// ─── Affichage des erreurs PHP ──────────────────────────────────────────────
if ($isProd) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');

    $logDir = ROOT_PATH . '/storage/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    ini_set('error_log', $logDir . '/php-errors.log');
}
error_reporting(E_ALL);

// ─── Session ────────────────────────────────────────────────────────────────
// Nom de cookie neutre (n'expose plus l'utilisation de PHP via 'PHPSESSID').
session_name('portfolio_sid');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $isProd,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// ─── Idle timeout admin (30 min) ────────────────────────────────────────────
$adminIdleMax = 1800;
if (!empty($_SESSION['admin'])) {
    $lastActivity = $_SESSION['admin_last_activity'] ?? time();
    if (time() - $lastActivity > $adminIdleMax) {
        $_SESSION = [];
        session_regenerate_id(true);
    } else {
        $_SESSION['admin_last_activity'] = time();
    }
}

// ─── CSRF token global ──────────────────────────────────────────────────────
csrf_token();

// ─── Headers de sécurité ────────────────────────────────────────────────────
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cross-Origin-Opener-Policy: same-origin');
header('Cross-Origin-Resource-Policy: same-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=(), accelerometer=(), gyroscope=()');

if ($isProd) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

$csp = "default-src 'self'; "
     . "script-src 'self'; "
     . "style-src 'self' https://fonts.googleapis.com; "
     . "font-src 'self' https://fonts.gstatic.com; "
     . "img-src 'self' data: https:; "
     . "connect-src 'self'; "
     . "frame-ancestors 'self'; "
     . "form-action 'self'; "
     . "base-uri 'self'; "
     . "object-src 'none'";

if ($isProd) {
    $csp .= "; upgrade-insecure-requests";

    // ─── Reporting CSP — visibilité sur les violations en prod ─────────
    // report-uri  : format legacy, encore utilisé par Firefox et anciens Chrome
    // report-to   : format moderne, nécessite le header Reporting-Endpoints
    $reportPath = '/csp-report';
    $csp .= "; report-uri {$reportPath}; report-to csp-endpoint";

    $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
    header('Reporting-Endpoints: csp-endpoint="' . $appUrl . $reportPath . '"');
}

header("Content-Security-Policy: " . $csp);

// ─── Langue par défaut ──────────────────────────────────────────────────────
$_SESSION['lang'] ??= $_ENV['DEFAULT_LANG'] ?? 'fr';

// ─── Router ─────────────────────────────────────────────────────────────────
$router = new Core\Router();
require ROOT_PATH . '/config/routes.php';
$router->dispatch();
