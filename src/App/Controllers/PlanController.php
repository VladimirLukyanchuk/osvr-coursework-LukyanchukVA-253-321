<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\View;
use App\Models\Article;

final class PlanController
{
    public function index(): void
    {
        $topics = Article::allTopics();
        $selection = [];
        $errors = [];
        $result = null;
        $daily = 45;
        $repetitions = 1;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['topics'] ?? [];
            if (!is_array($ids) || count($ids) > 50 || !$ids) {
                $errors[] = 'Выберите от 1 до 50 материалов.';
                $ids = [];
            }
            foreach ($ids as $id) {
                if (!is_string($id) || !ctype_digit($id)) {
                    $errors[] = 'Некорректный список материалов.';
                    break;
                }
                $selection[] = (int) $id;
            }
            $selection = array_unique($selection);
            $daily = filter_var(input('daily_minutes'), FILTER_VALIDATE_INT);
            $repetitions = filter_var(input('repetitions'), FILTER_VALIDATE_INT);
            if ($daily === false || $daily < 15 || $daily > 240) { $errors[] = 'Время в день: целое число от 15 до 240 минут.'; }
            if (!in_array($repetitions, [1, 2, 3], true)) { $errors[] = 'Количество прохождений: от 1 до 3.'; }
            $selected = array_filter($topics, static fn ($topic) => in_array((int) $topic['id'], $selection, true));
            if (count($selected) !== count($selection)) { $errors[] = 'Один из материалов больше не существует. Обновите страницу.'; }
            if (!$errors) {
                $total = array_sum(array_map(static fn ($topic) => $topic['read_minutes'] + $topic['practice_minutes'], $selected)) * $repetitions;
                $result = ['minutes' => $total, 'days' => (int) ceil($total / $daily), 'count' => count($selected)];
            } else {
                http_response_code(422);
            }
        }
        View::render('plan', compact('topics', 'selection', 'errors', 'result', 'daily', 'repetitions') + ['title' => 'План подготовки']);
    }
}
