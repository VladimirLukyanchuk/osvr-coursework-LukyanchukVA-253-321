<?php
declare(strict_types=1);
namespace App\Core;

final class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $user = Auth::user();
        $errors ??= [];
        $values ??= [];
        ob_start();
        require ROOT . '/views/' . $template . '.php';
        $content = ob_get_clean();
        $title ??= 'Вебпрактика';
        require ROOT . '/views/layout.php';
    }
}
