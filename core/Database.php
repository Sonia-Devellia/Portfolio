<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $name = $_ENV['DB_NAME'] ?? 'portfolio';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? '';

        try {
            self::$instance = new PDO(
                "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            // En prod : masquer le détail au client, le détail reste dans les logs.
            $isProd = ($_ENV['APP_ENV'] ?? 'local') === 'production';
            error_log('[DB] Connexion échouée : ' . $e->getMessage());

            throw new \RuntimeException(
                $isProd ? 'Erreur de connexion à la base de données.' : 'Connexion BDD échouée : ' . $e->getMessage()
            );
        }

        return self::$instance;
    }
}
