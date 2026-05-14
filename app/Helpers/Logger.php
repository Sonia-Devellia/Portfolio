<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Logger applicatif minimal — append-only, file-based, RGPD-friendly.
 *
 * Format :
 *   [2026-05-01T12:34:56+02:00] [SECURITY] login_failed | ip=ab12cd34 | ua=Mozilla/... | ctx={"user":"admin"}
 *
 * Rotation automatique :
 *   Chaque fichier est rotaté quand il dépasse MAX_SIZE (5 MB).
 *   On garde MAX_ARCHIVES versions (.1 à .5). Au-delà, les plus anciennes sont supprimées.
 *   Max disque : ~25 MB par type de log (security + app = 50 MB).
 *
 * Sécurité :
 *   IP hashée par défaut (SHA-256 tronqué), pas de PII en clair.
 *   Logs hors public/, jamais accessibles via HTTP (storage/.htaccess "Require all denied").
 */
class Logger
{
    private const DIR          = ROOT_PATH . '/storage/logs';
    private const MAX_SIZE     = 5 * 1024 * 1024; // 5 MB par fichier
    private const MAX_ARCHIVES = 5;               // .log.1 à .log.5

    public static function security(string $event, array $context = []): void
    {
        self::write('security.log', 'SECURITY', $event, $context);
    }

    public static function info(string $event, array $context = []): void
    {
        self::write('app.log', 'INFO', $event, $context);
    }

    public static function warning(string $event, array $context = []): void
    {
        self::write('app.log', 'WARNING', $event, $context);
    }

    public static function error(string $event, array $context = []): void
    {
        self::write('app.log', 'ERROR', $event, $context);
    }

    private static function write(string $file, string $level, string $event, array $context): void
    {
        if (!is_dir(self::DIR)) {
            @mkdir(self::DIR, 0750, true);
        }

        $path = self::DIR . '/' . $file;

        if (is_file($path) && filesize($path) >= self::MAX_SIZE) {
            self::rotate($path);
        }

        $line = sprintf(
            "[%s] [%s] %s | ip=%s | ua=%s | ctx=%s\n",
            date('c'),
            $level,
            $event,
            self::ipHash(),
            self::ua(),
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        @file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
    }

    /**
     * Rotation : security.log → .1, .1 → .2, ... La plus ancienne est supprimée.
     *
     * Ordre crucial : on déplace les plus hautes vers les plus hautes d'abord,
     * pour ne jamais écraser un fichier qui n'a pas encore été renommé.
     */
    private static function rotate(string $path): void
    {
        $oldest = $path . '.' . self::MAX_ARCHIVES;
        if (is_file($oldest)) {
            @unlink($oldest);
        }

        for ($i = self::MAX_ARCHIVES - 1; $i >= 1; $i--) {
            $from = $path . '.' . $i;
            $to   = $path . '.' . ($i + 1);
            if (is_file($from)) {
                @rename($from, $to);
            }
        }

        @rename($path, $path . '.1');
    }

    public static function ipHash(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '?';
        return substr(hash('sha256', $ip . ($_ENV['APP_URL'] ?? '')), 0, 16);
    }

    private static function ua(): string
    {
        return substr($_SERVER['HTTP_USER_AGENT'] ?? '?', 0, 200);
    }
}
