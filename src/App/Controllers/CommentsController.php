<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\{Auth, HttpException, View};
use App\Models\{Article, Comment};

final class CommentsController
{
    public function store(int $id): void
    {
        $user = Auth::requireUser();
        Article::find($id) ?? throw new HttpException(404, 'Статья не найдена.');
        $body = input('body');
        if (mb_strlen($body) < 3 || mb_strlen($body) > 2000) {
            http_response_code(422);
            (new ArticlesController())->display($id, ['Комментарий: от 3 до 2000 символов.'], ['comment' => $body]);
            return;
        }
        $comment = (new Comment())->fill(['author_id' => $user->id, 'article_id' => $id, 'body' => $body]);
        $comment->save();
        notice('Комментарий добавлен.');
        redirect('/articles/' . $id . '#comment' . $comment->id);
    }

    public function edit(int $id): void
    {
        $comment = $this->owned($id);
        View::render('comment-edit', ['title' => 'Редактирование комментария', 'comment' => $comment]);
    }

    public function update(int $id): void
    {
        $comment = $this->owned($id);
        $body = input('body');
        if (mb_strlen($body) < 3 || mb_strlen($body) > 2000) {
            http_response_code(422);
            View::render('comment-edit', ['title' => 'Редактирование комментария', 'comment' => $comment, 'values' => ['body' => $body], 'errors' => ['Комментарий: от 3 до 2000 символов.']]);
            return;
        }
        $comment->fill(['body' => $body])->save();
        notice('Комментарий изменён.');
        redirect('/articles/' . $comment->article_id . '#comment' . $id);
    }

    public function destroy(int $id): void
    {
        $comment = $this->owned($id);
        $articleId = $comment->article_id;
        $comment->delete();
        notice('Комментарий удалён.');
        redirect('/articles/' . $articleId . '#comments');
    }

    private function owned(int $id): Comment
    {
        Auth::requireUser();
        $comment = Comment::find($id) ?? throw new HttpException(404, 'Комментарий не найден.');
        Auth::own((int) $comment->author_id);
        return $comment;
    }
}
