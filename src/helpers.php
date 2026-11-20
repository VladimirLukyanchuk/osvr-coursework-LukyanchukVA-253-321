<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = '/'): string
{
    return rtrim(getenv('APP_BASE_PATH') ?: '', '/') . $path;
}

function csrf(): string
{
    return '<input type="hidden" name="_token" value="' . e($_SESSION['csrf']) . '">';
}

function redirect(string $path): never
{
    header('Location: ' . url($path), true, 303);
    exit;
}

function notice(string $message): void
{
    $_SESSION['notice'] = $message;
}

function input(string $key, string $default = ''): string
{
    return is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : $default;
}

function category(string $key): string
{
    return App\Models\Article::CATEGORIES[$key] ?? 'Веб-разработка';
}

function dateLabel(string $date): string
{
    return date('d.m.Y', strtotime($date));
}
