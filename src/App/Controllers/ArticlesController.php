<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\{Auth, Database, HttpException, View};
use App\Models\{Article, Comment};

final class ArticlesController
{
    public function home(): void
    {
        $stats = Database::connection()->query('SELECT COUNT(*) AS count, COALESCE(SUM(read_minutes + practice_minutes), 0) AS minutes FROM articles')->fetch();
        View::render('home', ['title' => 'Вебпрактика — учиться и пробовать', 'articles' => array_slice(Article::listing()['items'], 0, 3), 'stats' => $stats]);
    }

    public function index(): void
    {
        $q = is_string($_GET['q'] ?? null) ? mb_substr(trim($_GET['q']), 0, 100) : '';
        $category = is_string($_GET['category'] ?? null) ? $_GET['category'] : '';
        $sort = ($_GET['sort'] ?? '') === 'shortest' ? 'shortest' : 'newest';
        $listing = Article::listing($q, $category, $sort, (int) ($_GET['page'] ?? 1));
        View::render('materials', compact('listing', 'q', 'category', 'sort') + ['title' => 'Материалы']);
    }

    public function show(int $id): void
    {
        $this->display($id);
    }

    public function display(int $id, array $errors = [], array $values = []): void
    {
        $article = Article::find($id) ?? throw new HttpException(404, 'Статья не найдена.');
        $author = $article->author();
        $comments = Comment::forArticle($id);
        View::render('article', compact('article', 'author', 'comments', 'errors', 'values') + ['title' => $article->title]);
    }

    public function cabinet(): void
    {
        $user = Auth::requireUser();
        $listing = Article::listing(authorId: (int) $user->id, page: (int) ($_GET['page'] ?? 1));
        View::render('cabinet', compact('listing') + ['title' => 'Мои материалы']);
    }

    public function create(): void
    {
        Auth::requireUser();
        $this->editor(new Article());
    }

    public function edit(int $id): void
    {
        $article = $this->owned($id);
        $this->editor($article);
    }

    public function store(): void
    {
        $user = Auth::requireUser();
        $this->save(new Article(), (int) $user->id);
    }

    public function update(int $id): void
    {
        $article = $this->owned($id);
        $this->save($article, (int) $article->author_id);
    }

    private function save(Article $article, int $authorId): void
    {
        $values = [];
        foreach (['title', 'summary', 'body', 'category', 'read_minutes', 'practice_minutes'] as $key) {
            $values[$key] = input($key);
        }
        $errors = [];
        foreach (['title' => [5, 150, 'Заголовок'], 'summary' => [15, 350, 'Краткое описание'], 'body' => [50, 15000, 'Текст статьи']] as $key => [$min, $max, $label]) {
            if (mb_strlen($values[$key]) < $min || mb_strlen($values[$key]) > $max) {
                $errors[] = "$label: от $min до $max символов.";
            }
        }
        if (!isset(Article::CATEGORIES[$values['category']])) {
            $errors[] = 'Выберите тему из списка.';
        }
        foreach (['read_minutes' => [1, 180], 'practice_minutes' => [0, 480]] as $key => [$min, $max]) {
            $number = filter_var($values[$key], FILTER_VALIDATE_INT);
            if ($number === false || $number < $min || $number > $max) {
                $errors[] = 'Время чтения: 1–180 минут, практики: 0–480 минут. Введите целые числа.';
            }
        }
        if ($errors) {
            http_response_code(422);
            $this->editor($article, $values, $errors);
            return;
        }
        $article->fill($values + ['author_id' => $authorId])->save();
        notice('Материал опубликован. Изменить его можно в личном кабинете.');
        redirect('/articles/' . $article->id);
    }

    private function owned(int $id): Article
    {
        Auth::requireUser();
        $article = Article::find($id) ?? throw new HttpException(404, 'Статья не найдена.');
        Auth::own((int) $article->author_id);
        return $article;
    }

    public function destroy(int $id): void
    {
        $this->owned($id)->delete();
        notice('Материал удалён.');
        redirect('/cabinet');
    }

    private function editor(Article $article, array $values = [], array $errors = []): void
    {
        View::render('editor', compact('article', 'values', 'errors') + ['title' => $article->id ? 'Редактирование материала' : 'Новый материал']);
    }
}
