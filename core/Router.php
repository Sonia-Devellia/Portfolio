<?php

declare(strict_types=1);

namespace Core;

class Router
{
    /** @var list<array{method:string,path:string,controller:string,action:string}> */
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void
    {
        $this->routes[] = compact('path', 'controller', 'action') + ['method' => 'GET'];
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->routes[] = compact('path', 'controller', 'action') + ['method' => 'POST'];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        // Retirer le base path si en sous-dossier (MAMP local)
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($base && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . trim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $pattern = '#^' . preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $route['path']) . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $class = 'App\\Controllers\\' . $route['controller'];
                (new $class())->{$route['action']}(...$matches);
                return;
            }
        }

        http_response_code(404);
        (new \App\Controllers\HomeController())->notFound();
    }
}
