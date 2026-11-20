<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\{ActiveRecord, Database};

final class Article extends ActiveRecord
{
    public const CATEGORIES = ['php' => 'PHP', 'database' => 'Базы данных', 'web' => 'Веб-технологии'];
    protected static string $table = 'articles';
    protected static array $fields = ['author_id', 'title', 'summary', 'body', 'category', 'read_minutes', 'practice_minutes'];

    public function author(): ?User
    {
        return User::find((int) $this->author_id);
    }

    public static function listing(string $query = '', string $category = '', string $sort = 'newest', int $page = 1, ?int $authorId = null): array
    {
        $conditions = ['1 = 1'];
        $params = [];
        if ($query !== '') {
            $conditions[] = '(LOWER_UTF8(a.title) LIKE ? OR LOWER_UTF8(a.summary) LIKE ?)';
            $search = '%' . mb_strtolower($query, 'UTF-8') . '%';
            $params = [$search, $search];
        }
        if (isset(self::CATEGORIES[$category])) {
            $conditions[] = 'a.category = ?';
            $params[] = $category;
        }
        if ($authorId !== null) {
            $conditions[] = 'a.author_id = ?';
            $params[] = $authorId;
        }
        $where = implode(' AND ', $conditions);
        $pdo = Database::connection();
        $count = $pdo->prepare("SELECT COUNT(*) FROM articles a WHERE $where");
        $count->execute($params);
        $total = (int) $count->fetchColumn();
        $pages = max(1, (int) ceil($total / 6));
        $page = min(max(1, $page), $pages);
        $offset = ($page - 1) * 6;
        $order = $sort === 'shortest' ? 'a.read_minutes + a.practice_minutes, a.id DESC' : 'a.id DESC';
        $statement = $pdo->prepare("SELECT a.*, u.name AS author_name FROM articles a JOIN users u ON u.id = a.author_id WHERE $where ORDER BY $order LIMIT 6 OFFSET $offset");
        $statement->execute($params);
        return ['items' => array_map(static fn ($row) => new self($row), $statement->fetchAll()), 'total' => $total, 'pages' => $pages, 'page' => $page];
    }

    public static function allTopics(): array
    {
        return Database::connection()->query('SELECT id, title, category, read_minutes, practice_minutes FROM articles ORDER BY category, title')->fetchAll();
    }
}
