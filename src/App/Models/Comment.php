<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\{ActiveRecord, Database};

final class Comment extends ActiveRecord
{
    protected static string $table = 'comments';
    protected static array $fields = ['author_id', 'article_id', 'body'];

    public static function forArticle(int $articleId): array
    {
        $query = Database::connection()->prepare('SELECT c.*, u.name AS author_name FROM comments c JOIN users u ON u.id = c.author_id WHERE article_id = ? ORDER BY c.id');
        $query->execute([$articleId]);
        return array_map(static fn ($row) => new self($row), $query->fetchAll());
    }
}
