<?php

namespace App\Models;

use Core\Database;
use PDO;

class Project
{
    public static function getAll(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query('SELECT * FROM projects ORDER BY sort_order ASC, id DESC');
        return $stmt->fetchAll();
    }

    public static function getFeatured(int $limit = 4): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE is_featured = 1 ORDER BY sort_order ASC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getBySlug(string $slug): array|false
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE slug = ?');
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function getById(int $id): array|false
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create(array $data): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            INSERT INTO projects
                (title_fr, title_en, desc_fr, desc_en, slug, tags, github_url, demo_url, thumbnail, is_featured, is_wip, sort_order)
            VALUES
                (:title_fr, :title_en, :desc_fr, :desc_en, :slug, :tags, :github_url, :demo_url, :thumbnail, :is_featured, :is_wip, :sort_order)
        ');
        $stmt->execute($data);
    }

    public static function update(int $id, array $data): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            UPDATE projects SET
                title_fr    = :title_fr,
                title_en    = :title_en,
                desc_fr     = :desc_fr,
                desc_en     = :desc_en,
                slug        = :slug,
                tags        = :tags,
                github_url  = :github_url,
                demo_url    = :demo_url,
                thumbnail   = :thumbnail,
                is_featured = :is_featured,
                is_wip      = :is_wip,
                sort_order  = :sort_order
            WHERE id = :id
        ');
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public static function delete(int $id): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * Retourne les tags d'un projet sous forme de tableau
     */
    public static function parseTags(string $tags): array
    {
        return array_filter(array_map('trim', explode(',', $tags)));
    }
}
