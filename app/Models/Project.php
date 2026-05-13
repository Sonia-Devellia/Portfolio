<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;

class Project
{
    public static function getAll(): array
    {
        return Database::getInstance()
            ->query('SELECT * FROM projects ORDER BY sort_order ASC, id DESC')
            ->fetchAll();
    }

    public static function getById(int $id): array|false
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM projects WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create(array $data): void
    {
        Database::getInstance()->prepare(<<<SQL
            INSERT INTO projects
                (title_fr, title_en, desc_fr, desc_en, slug, tags, github_url, demo_url,
                 thumbnail, is_featured, is_wip, sort_order)
            VALUES
                (:title_fr, :title_en, :desc_fr, :desc_en, :slug, :tags, :github_url, :demo_url,
                 :thumbnail, :is_featured, :is_wip, :sort_order)
            SQL)->execute($data);
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        Database::getInstance()->prepare(<<<SQL
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
            SQL)->execute($data);
    }

    public static function delete(int $id): void
    {
        Database::getInstance()
            ->prepare('DELETE FROM projects WHERE id = ?')
            ->execute([$id]);
    }
}
