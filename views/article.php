<article class="reading">
    <a class="back" href="<?= e(url('/materials')) ?>">Все материалы</a>
    <header class="article-heading"><span class="category"><?= e(category($article->category)) ?></span><h1><?= e($article->title) ?></h1><p class="lead"><?= e($article->summary) ?></p>
        <div class="author-line"><span><?= e($author?->name ?? 'Автор') ?></span><time datetime="<?= e($article->created_at) ?>"><?= e(dateLabel($article->created_at)) ?></time><span><?= (int) $article->read_minutes ?> мин чтения · <?= (int) $article->practice_minutes ?> мин практики</span></div>
    </header>
    <div class="article-body"><?php foreach (preg_split('/\R\s*\R/u', $article->body) as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?></div>
    <div class="reading-end"><p>Заложи время на практику — так тема запомнится лучше.</p><div class="actions"><a class="button small" href="<?= e(url('/plan')) ?>">Составить план</a><?php if ($user && (int) $user->id === (int) $article->author_id): ?><a href="<?= e(url('/cabinet/' . $article->id . '/edit')) ?>">Редактировать статью</a><?php endif; ?></div></div>
    <section class="comments" id="comments">
        <div class="section-top"><h2>Обсуждение</h2><span class="muted"><?= count($comments) ?> <?= wordForm(count($comments), ['комментарий', 'комментария', 'комментариев']) ?></span></div>
        <?php if (!$comments): ?><p class="muted">Здесь пока тихо. Поделись вопросом или результатом практики.</p><?php endif; ?>
        <?php foreach ($comments as $comment): ?>
        <article class="comment panel" id="comment<?= (int) $comment->id ?>">
            <div class="comment-meta"><strong><?= e($comment->author_name) ?></strong><time><?= e(dateLabel($comment->created_at)) ?></time></div><p><?= e($comment->body) ?></p>
            <?php if ($user && (int) $user->id === (int) $comment->author_id): ?><div class="actions"><a href="<?= e(url('/comments/' . $comment->id . '/edit')) ?>">Редактировать</a><form class="delete-form" method="post" action="<?= e(url('/comments/' . $comment->id . '/delete')) ?>"><?= csrf() ?><button class="text-button danger">Удалить</button></form></div><?php endif; ?>
        </article>
        <?php endforeach; ?>
        <?php if ($user): ?><form class="panel form" method="post" action="<?= e(url('/articles/' . $article->id . '/comments')) ?>"><?= csrf() ?><label>Твой комментарий<textarea name="body" rows="4" minlength="3" maxlength="2000" required><?= e($values['comment'] ?? '') ?></textarea></label><div><button class="button">Отправить комментарий</button></div></form>
        <?php else: ?><div class="panel"><p>Чтобы участвовать в обсуждении, войди в аккаунт.</p><a class="button small" href="<?= e(url('/login')) ?>">Войти</a> <a href="<?= e(url('/register')) ?>">Создать аккаунт</a></div><?php endif; ?>
    </section>
</article>
