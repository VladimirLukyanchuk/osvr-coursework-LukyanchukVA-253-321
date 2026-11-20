<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\View;
use App\Models\Feedback;

final class FeedbackController
{
    public function index(): void
    {
        $errors = [];
        $values = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach (['name', 'email', 'type', 'message'] as $key) { $values[$key] = input($key); }
            if (mb_strlen($values['name']) < 2 || mb_strlen($values['name']) > 100) { $errors[] = 'Имя: от 2 до 100 символов.'; }
            if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || strlen($values['email']) > 200) { $errors[] = 'Укажите корректный email.'; }
            if (!in_array($values['type'], ['question', 'suggestion', 'error'], true)) { $errors[] = 'Выберите тип обращения.'; }
            if (mb_strlen($values['message']) < 10 || mb_strlen($values['message']) > 3000) { $errors[] = 'Сообщение: от 10 до 3000 символов.'; }
            if (!$errors) {
                (new Feedback())->fill($values)->save();
                notice('Обращение сохранено. Спасибо за обратную связь!');
                redirect('/feedback');
            }
            http_response_code(422);
        }
        View::render('feedback', compact('errors', 'values') + ['title' => 'Обратная связь']);
    }
}
