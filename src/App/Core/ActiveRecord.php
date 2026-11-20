<?php
declare(strict_types=1);
namespace App\Core;

abstract class ActiveRecord
{
    protected static string $table;
    protected static array $fields;

    public function __construct(protected array $attributes = []) {}

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public static function find(int $id): ?static
    {
        return static::one('id = ?', [$id]);
    }

    protected static function one(string $condition, array $values): ?static
    {
        $statement = Database::connection()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . $condition . ' LIMIT 1');
        $statement->execute($values);
        $row = $statement->fetch();
        return $row ? new static($row) : null;
    }

    public function fill(array $data): static
    {
        foreach (static::$fields as $field) {
            if (array_key_exists($field, $data)) {
                $this->attributes[$field] = $data[$field];
            }
        }
        return $this;
    }

    public function save(): void
    {
        $values = array_intersect_key($this->attributes, array_flip(static::$fields));
        $pdo = Database::connection();
        if ($this->id !== null) {
            $sets = implode(', ', array_map(static fn ($key) => "$key = ?", array_keys($values)));
            $statement = $pdo->prepare('UPDATE ' . static::$table . ' SET ' . $sets . ', updated_at = CURRENT_TIMESTAMP WHERE id = ?');
            $statement->execute([...array_values($values), $this->id]);
        } else {
            $columns = implode(', ', array_keys($values));
            $placeholders = implode(', ', array_fill(0, count($values), '?'));
            $statement = $pdo->prepare('INSERT INTO ' . static::$table . " ($columns) VALUES ($placeholders)");
            $statement->execute(array_values($values));
            $this->attributes['id'] = (int) $pdo->lastInsertId();
        }
    }

    public function delete(): void
    {
        $statement = Database::connection()->prepare('DELETE FROM ' . static::$table . ' WHERE id = ?');
        $statement->execute([$this->id]);
    }
}
