<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;
use Throwable;

/**
 * Endpoint de monitoring — consommé par UptimeRobot, Better Stack, etc.
 *
 * GET /health    → {"status":"ok","db":"ok","time":"..."}    HTTP 200
 * GET /health    → {"status":"degraded","db":"fail",...}     HTTP 503
 *
 * Pas d'authentification : le monitoring doit pouvoir hit sans token.
 * Pas de Logger : avec un check toutes les 5min, on génèrerait 288 logs/jour pour rien.
 * Réponse JSON minimaliste, < 100 ms cible.
 */
class HealthController extends Controller
{
    public function index(): void
    {
        $checks = [
            'db' => $this->checkDatabase(),
        ];

        $ok = !in_array(false, $checks, true);

        $this->json([
            'status' => $ok ? 'ok' : 'degraded',
            'time'   => date('c'),
            'checks' => array_map(static fn(bool $r): string => $r ? 'ok' : 'fail', $checks),
        ], $ok ? 200 : 503);
    }

    private function checkDatabase(): bool
    {
        try {
            Database::getInstance()->query('SELECT 1')->fetch();
            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
