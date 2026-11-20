<section class="panel auth-box"><p class="eyebrow">Вебпрактика</p><h1><?= $register ? 'Присоединяйся' : 'С возвращением' ?></h1><p class="muted"><?= $register ? 'Делись материалами и участвуй в обсуждениях.' : 'Войди, чтобы продолжить работу со своими материалами.' ?></p>
<form class="form" method="post" action="<?= e(url($register ? '/register' : '/login')) ?>"><?= csrf() ?>
    <?php if ($register): ?><label>Имя<input name="name" required minlength="2" maxlength="100" autocomplete="name" value="<?= e($values['name'] ?? '') ?>"></label><?php endif; ?>
    <label>Email<input name="email" type="email" required maxlength="200" autocomplete="email" value="<?= e($values['email'] ?? '') ?>"></label>
    <label>Пароль<input type="password" name="password" minlength="8" maxlength="72" required autocomplete="<?= $register ? 'new-password' : 'current-password' ?>"></label>
    <?php if ($register): ?><label>Повтори пароль<input type="password" name="password_confirmation" minlength="8" maxlength="72" required autocomplete="new-password"></label><?php endif; ?>
    <button class="button"><?= $register ? 'Создать аккаунт' : 'Войти' ?></button>
</form><p><?= $register ? 'Уже есть аккаунт?' : 'Ещё нет аккаунта?' ?> <a href="<?= e(url($register ? '/login' : '/register')) ?>"><?= $register ? 'Войти' : 'Зарегистрироваться' ?></a></p></section>
