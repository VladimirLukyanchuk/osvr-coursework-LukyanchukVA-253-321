<?php
declare(strict_types=1);
namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function user(): ?User
    {
        return isset($_SESSION['user_id']) ? User::find((int) $_SESSION['user_id']) : null;
    }

    public static function requireUser(): User
    {
        $user = self::user();
        if ($user === null) {
            notice('Войдите в аккаунт, чтобы продолжить.');
            redirect('/login');
        }
        return $user;
    }

    public static function login(User $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    public static function own(int $authorId): void
    {
        if ((int) self::requireUser()->id !== $authorId) {
            throw new HttpException(403, 'Изменять можно только свои публикации.');
        }
    }
}
