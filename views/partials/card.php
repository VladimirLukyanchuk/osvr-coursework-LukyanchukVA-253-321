<article class="material-card">
    <div class="card-meta"><span class="category"><?= e(category($article->category)) ?></span><span><?= (int) $article->read_minutes + (int) $article->practice_minutes ?> мин с практикой</span></div>
    <h3><a href="<?= e(url('/articles/' . $article->id)) ?>"><?= e($article->title) ?></a></h3>
    <p><?= e($article->summary) ?></p>
    <div class="card-bottom"><span><?= e($article->author_name) ?></span><time datetime="<?= e($article->created_at) ?>"><?= e(dateLabel($article->created_at)) ?></time></div>
</article>
