<section class="page-heading"><p class="eyebrow">Библиотека</p><h1>Найди свою следующую тему</h1><p class="lead">От устройства HTTP до авторизации. Читай в своём темпе и закрепляй знания на практике.</p></section>
<form class="filters" method="get" action="<?= e(url('/materials')) ?>">
    <label>Поиск<input name="q" maxlength="100" placeholder="Например, сессии или SQLite" value="<?= e($q) ?>"></label>
    <label>Тема<select name="category"><option value="">Все темы</option><?php foreach (App\Models\Article::CATEGORIES as $key => $label): ?><option value="<?= e($key) ?>" <?= $key === $category ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
    <label>Порядок<select name="sort"><option value="newest">Сначала новые</option><option value="shortest" <?= $sort === 'shortest' ? 'selected' : '' ?>>Сначала короткие</option></select></label>
    <button class="button">Найти</button>
</form>
<p class="muted">Найдено материалов: <?= $listing['total'] ?></p>
<section class="section">
    <?php if ($listing['items']): ?><div class="card-grid"><?php foreach ($listing['items'] as $article): require ROOT . '/views/partials/card.php'; endforeach; ?></div>
    <?php else: ?><div class="panel empty"><h2>Пока ничего не нашлось</h2><p>Попробуй другое слово или убери фильтр по теме.</p><a href="<?= e(url('/materials')) ?>">Показать все материалы</a></div><?php endif; ?>
    <?php require ROOT . '/views/partials/pagination.php'; ?>
</section>
