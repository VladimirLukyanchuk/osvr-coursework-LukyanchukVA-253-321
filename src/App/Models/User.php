<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\ActiveRecord;

final class User extends ActiveRecord
{
    protected static string $table = 'users';
    protected static array $fields = ['name', 'email', 'password'];

    public static function byEmail(string $email): ?self
    {
        return self::one('email = ?', [$email]);
    }
}
