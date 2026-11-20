<?php
declare(strict_types=1);
require dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self'; img-src 'self' data:; form-action 'self'; base-uri 'self'; frame-ancestors 'self'");
try {
    $router = new App\Core\Router();
    require ROOT . '/routes.php';
    $router->dispatch();
} catch (App\Core\HttpException $error) {
    http_response_code($error->status);
    App\Core\View::render('error', ['title' => 'Ошибка ' . $error->status, 'status' => $error->status, 'message' => $error->getMessage()]);
} catch (Throwable $error) {
    error_log((string) $error);
    http_response_code(500);
    App\Core\View::render('error', ['title' => 'Ошибка сервера', 'status' => 500, 'message' => 'Не удалось выполнить запрос. Попробуйте позже.']);
}
