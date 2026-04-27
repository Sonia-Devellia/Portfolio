<?php

namespace Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        // Charger les traductions
        $lang = $_SESSION['lang'] ?? 'fr';
        $translations = require ROOT_PATH . '/lang/' . $lang . '.php';

        // Fonction helper t()
        $t = function (string $key) use ($translations): string {
            return htmlspecialchars($translations[$key] ?? $key, ENT_QUOTES, 'UTF-8');
        };

        // Rendre les données disponibles dans la vue
        extract($data);

        // Chemin de la vue
        $viewPath = ROOT_PATH . '/app/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : {$view}");
        }

        // Capturer le contenu de la vue
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Charger le layout
        $layout = $data['layout'] ?? 'layouts/main';
        require ROOT_PATH . '/app/Views/' . $layout . '.php';
    }

    protected function redirect(string $path): void
    {
        $base = rtrim($_ENV['APP_URL'] ?? '', '/');
        header('Location: ' . $base . $path);
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
