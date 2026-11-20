<?php
declare(strict_types=1);

define('ROOT', __DIR__);
date_default_timezone_set('Europe/Moscow');
spl_autoload_register(static function (string $class): void {
    $file = ROOT . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require $file;
    }
});
require ROOT . '/src/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
