<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Материалы о PHP и веб-разработке, обсуждения и личный план подготовки.">
    <title><?= e($title) ?> · Вебпрактика</title>
    <link rel="icon" href="<?= e(url('/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= e(url('/assets/site.css')) ?>?v=<?= substr(hash_file('sha256', ROOT . '/public/assets/site.css'), 0, 12) ?>">
    <script src="<?= e(url('/assets/plan.js')) ?>" defer></script>
</head>
<body>
<a class="skip" href="#main">К содержимому</a>
<header class="header"><div class="wrap header-row">
    <a class="brand" href="<?= e(url()) ?>">веб<span>практика.</span></a>
    <nav class="navigation" aria-label="Основное меню">
        <a href="<?= e(url('/materials')) ?>">Материалы</a>
        <a href="<?= e(url('/plan')) ?>">План подготовки</a>
        <a href="<?= e(url('/feedback')) ?>">Обратная связь</a>
    </nav>
    <div class="account">
        <?php if ($user): ?>
            <a href="<?= e(url('/cabinet')) ?>">Мои материалы</a>
            <form method="post" action="<?= e(url('/logout')) ?>"><?= csrf() ?><button class="text-button">Выйти</button></form>
        <?php else: ?>
            <a href="<?= e(url('/login')) ?>">Войти</a>
            <a class="button small" href="<?= e(url('/register')) ?>">Регистрация</a>
        <?php endif; ?>
    </div>
</div></header>
<main id="main" class="wrap">
    <?php if (isset($_SESSION['notice'])): ?>
        <div class="notice" role="status"><?= e($_SESSION['notice']) ?></div>
        <?php unset($_SESSION['notice']); ?>
    <?php endif; ?>
    <?php if ($errors): ?>
        <div class="notice error" role="alert"><strong>Проверьте данные</strong><ul><?php foreach (array_unique($errors) as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <?= $content ?>
</main>
<footer class="footer"><div class="wrap footer-row"><div><a class="brand" href="<?= e(url()) ?>">веб<span>практика.</span></a><p>Изучать. Пробовать. Делиться опытом.</p></div><p>Курсовой проект по ОСВР<br>Лукьянчук Владимир Алексеевич · 253-321</p></div></footer>
</body>
</html>
