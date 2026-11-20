<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">PHP · Данные · Веб</p>
        <h1>От первого запроса<br> к работающему сайту.</h1>
        <p class="lead">Короткие материалы, понятные примеры и практика. Выбери темы, которые хочешь разобрать, и составь свой план подготовки.</p>
        <div class="actions"><a class="button" href="<?= e(url('/materials')) ?>">Выбрать материал</a><a class="button secondary" href="<?= e(url('/plan')) ?>">Рассчитать план</a></div>
    </div>
    <aside class="study-card" aria-label="Как заниматься">
        <div class="card-heading"><span>Твой следующий шаг</span><span class="symbol" aria-hidden="true">&lt;/&gt;</span></div>
        <h2>Прочитай.<br> Попробуй.<br> Разберись.</h2>
        <p>Каждый разбор заканчивается заданием, которое можно выполнить на своём компьютере.</p>
        <div class="stats"><div><strong><?= e($stats['count']) ?></strong><span>материалов</span></div><div><strong><?= e($stats['minutes']) ?></strong><span>минут с практикой</span></div></div>
    </aside>
</section>
<section class="section">
    <div class="section-top"><div><p class="eyebrow">Библиотека</p><h2>Что разобрать сегодня</h2></div><a href="<?= e(url('/materials')) ?>">Все материалы</a></div>
    <div class="card-grid"><?php foreach ($articles as $article): require ROOT . '/views/partials/card.php'; endforeach; ?></div>
</section>
<section class="invitation"><div><h2>Есть свой разбор темы?</h2><p>Опубликуй материал и обсуди его с другими читателями.</p></div><a class="button" href="<?= e(url('/cabinet/create')) ?>">Добавить материал</a></section>
