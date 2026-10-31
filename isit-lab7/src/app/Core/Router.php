<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void { $this->add('GET', $path, $handler); }
    public function post(string $path, array $handler): void { $this->add('POST', $path, $handler); }
    public function put(string $path, array $handler): void { $this->add('PUT', $path, $handler); }
    public function patch(string $path, array $handler): void { $this->add('PATCH', $path, $handler); }
    public function delete(string $path, array $handler): void { $this->add('DELETE', $path, $handler); }

    private function add(string $method, string $path, array $handler): void
    {
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/') ?: '/';
        $allowedMethods = [];

        foreach ($this->routes as $route) {
            $params = $this->match($route['path'], $path);
            if ($params === null) continue;

            if ($route['method'] !== $method) {
                $allowedMethods[] = $route['method'];
                continue;
            }

            [$class, $action] = $route['handler'];
            $controller = new $class();
            $controller->$action(...$params);
            return;
        }

        // Если адрес есть, но метод не тот
        if ($allowedMethods) {
            header('Allow: ' . implode(', ', array_unique($allowedMethods)));
            if (str_starts_with($path, '/api/')) {
                json_error('Метод не разрешён', 405);
            }
            http_response_code(405);
            echo '405 Method Not Allowed';
            exit;
        }

        // Если адреса вообще нет
        if (str_starts_with($path, '/api/')) {
            json_error('Ресурс не найден', 404);
        }
        notFound();
    }

    private function match(string $pattern, string $path): ?array
    {
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';
        if (!preg_match($regex, $path, $matches)) {
            return null;
        }
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }
}