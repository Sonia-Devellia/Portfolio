<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes[] = [
            'method'     => 'GET',
            'path'       => $path,
            'controller' => $controller,
            'action'     => $method,
        ];
    }

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes[] = [
            'method'     => 'POST',
            'path'       => $path,
            'controller' => $controller,
            'action'     => $method,
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Retirer le base path si en sous-dossier local
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($basePath && str_starts_with($requestUri, $basePath)) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        $requestUri = '/' . trim($requestUri, '/');

        foreach ($this->routes as $route) {
            $pattern = $this->pathToRegex($route['path']);
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);
                $controllerClass = 'App\\Controllers\\' . $route['controller'];
                $controller      = new $controllerClass();
                call_user_func_array([$controller, $route['action']], $matches);
                return;
            }
        }

        // 404
        http_response_code(404);
        $controller = new \App\Controllers\HomeController();
        $controller->notFound();
    }

    private function pathToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
