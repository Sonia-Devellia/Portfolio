<?php

declare(strict_types=1);

namespace Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $lang         = $_SESSION['lang'] ?? 'fr';
        $translations = require ROOT_PATH . '/lang/' . $lang . '.php';

        extract($data);

        // Helper de traduction — toujours échappé.
        $t = static fn(string $key): string => e($translations[$key] ?? $key);

        // Variante non échappée — réservée aux clés contenant du HTML maîtrisé (jamais user input).
        $tRaw = static fn(string $key): string => $translations[$key] ?? $key;

        $viewPath = ROOT_PATH . '/app/Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : {$view}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $layout = $data['layout'] ?? 'layouts/main';
        require ROOT_PATH . '/app/Views/' . $layout . '.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . base_url() . $path);
        exit;
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
