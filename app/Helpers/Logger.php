<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Logger applicatif minimal — append-only, file-based.
 *
 * Format ligne :
 *   [2026-05-01T12:34:56+02:00] [SECURITY] login_failed | ip=1.2.3.4 | ua=Mozilla/... | ctx={"user":"admin"}
 *
 * Les logs sont écrits dans storage/logs/ — hors de public/, jamais accessibles via HTTP.
 */
class Logger
{
    private const DIR = ROOT_PATH . '/storage/logs';

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

        $line = sprintf(
            "[%s] [%s] %s | ip=%s | ua=%s | ctx=%s\n",
            date('c'),
            $level,
            $event,
            self::ip(),
            self::ua(),
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        @file_put_contents(self::DIR . '/' . $file, $line, FILE_APPEND | LOCK_EX);
    }

    /**
     * Retourne un hash court de l'IP (RGPD-friendly : pas de PII en clair).
     */
    public static function ipHash(): string
    {
        return substr(hash('sha256', self::ip() . ($_ENV['APP_URL'] ?? '')), 0, 16);
    }

    private static function ip(): string
    {
        // Pas de FORWARDED_FOR sans whitelist proxy → simple, fiable
        return $_SERVER['REMOTE_ADDR'] ?? '?';
    }

    private static function ua(): string
    {
        return substr($_SERVER['HTTP_USER_AGENT'] ?? '?', 0, 200);
    }
}
