<?php
declare(strict_types=1);
namespace App\Core;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }
        $path = getenv('COURSE_DB_PATH') ?: ROOT . '/storage/course.sqlite';
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }
        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA foreign_keys = ON; PRAGMA busy_timeout = 5000;');
        $pdo->sqliteCreateFunction('LOWER_UTF8', static fn ($value) => mb_strtolower((string) $value, 'UTF-8'), 1);
        $pdo->exec(file_get_contents(ROOT . '/database/schema.sql'));
        self::$connection = $pdo;
        if (!$pdo->query('SELECT COUNT(*) FROM seed_versions WHERE version = 1')->fetchColumn()) {
            $pdo->beginTransaction();
            try {
                (require ROOT . '/database/seed.php')($pdo);
                $pdo->exec('INSERT INTO seed_versions (version) VALUES (1)');
                $pdo->commit();
            } catch (\Throwable $error) {
                $pdo->rollBack();
                throw $error;
            }
        }
        return $pdo;
    }
}
