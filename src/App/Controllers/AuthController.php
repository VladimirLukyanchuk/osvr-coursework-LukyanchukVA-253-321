<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\{Auth, View};
use App\Models\User;

final class AuthController
{
    public function login(): void { $this->form(false); }
    public function register(): void { $this->form(true); }

    private function form(bool $register, array $errors = []): void
    {
        if (Auth::user()) {
            redirect('/cabinet');
        }
        View::render('auth', ['title' => $register ? 'Регистрация' : 'Вход', 'register' => $register, 'errors' => $errors, 'values' => ['name' => input('name'), 'email' => input('email')]]);
    }

    public function signUp(): void
    {
        $name = input('name');
        $email = mb_strtolower(input('email'));
        $password = input('password');
        $errors = [];
        if (mb_strlen($name) < 2 || mb_strlen($name) > 100) { $errors[] = 'Имя: от 2 до 100 символов.'; }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 200) { $errors[] = 'Укажите корректный email.'; }
        if (strlen($password) < 8 || strlen($password) > 72) { $errors[] = 'Пароль: от 8 до 72 байт.'; }
        if ($password !== input('password_confirmation')) { $errors[] = 'Пароли не совпадают.'; }
        if (User::byEmail($email)) { $errors[] = 'Этот email уже зарегистрирован.'; }
        if ($errors) {
            http_response_code(422);
            $this->form(true, $errors);
            return;
        }
        $user = (new User())->fill(['name' => $name, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
        try {
            $user->save();
        } catch (\PDOException $error) {
            if ((string) $error->getCode() !== '23000') { throw $error; }
            http_response_code(422);
            $this->form(true, ['Этот email уже зарегистрирован.']);
            return;
        }
        Auth::login($user);
        notice('Аккаунт создан. Теперь можно публиковать материалы и оставлять комментарии.');
        redirect('/cabinet');
    }

    public function signIn(): void
    {
        $attempts = array_filter($_SESSION['login_attempts'] ?? [], static fn ($time) => $time > time() - 60);
        if (count($attempts) >= 5) {
            http_response_code(429);
            $this->form(false, ['Слишком много попыток. Подождите минуту.']);
            return;
        }
        $user = User::byEmail(input('email'));
        if (!$user || !password_verify(input('password'), $user->password)) {
            $_SESSION['login_attempts'] = [...$attempts, time()];
            http_response_code(422);
            $this->form(false, ['Неверный email или пароль.']);
            return;
        }
        unset($_SESSION['login_attempts']);
        Auth::login($user);
        redirect('/cabinet');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        notice('Вы вышли из аккаунта.');
        redirect('/');
    }
}
