<?php
declare(strict_types=1);
namespace App\Core;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $pattern = preg_replace('/\{id\}/', '([1-9][0-9]{0,9})', $path);
        $this->routes[] = [$method, '~^' . $pattern . '$~D', $handler];
    }

    public function dispatch(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        $base = rtrim(getenv('APP_BASE_PATH') ?: '', '/');
        if ($base && !str_starts_with($path, $base . '/') && $path !== $base) {
            throw new HttpException(404, 'Страница не найдена.');
        }
        $path = $base ? (substr($path, strlen($base)) ?: '/') : $path;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            $token = $_POST['_token'] ?? '';
            if (!is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
                throw new HttpException(419, 'Форма устарела. Обновите страницу и попробуйте ещё раз.');
            }
        }
        $allowed = [];
        foreach ($this->routes as [$verb, $pattern, $handler]) {
            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }
            $allowed[] = $verb;
            if ($verb !== $method && !($method === 'HEAD' && $verb === 'GET')) {
                continue;
            }
            array_shift($matches);
            [$class, $action] = $handler;
            (new $class())->$action(...array_map('intval', $matches));
            return;
        }
        if ($allowed) {
            header('Allow: ' . implode(', ', $allowed));
            throw new HttpException(405, 'Этот способ запроса не поддерживается.');
        }
        throw new HttpException(404, 'Страница не найдена.');
    }
}
