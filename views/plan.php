<section class="page-heading"><p class="eyebrow">Без спешки</p><h1>Твой план подготовки</h1><p class="lead">Выбери материалы и время на занятия. Рассчитаем, сколько дней понадобится на чтение и практику.</p></section>
<div class="planner section">
    <form class="panel form" id="planner" method="post" action="<?= e(url('/plan')) ?>"><?= csrf() ?>
        <div><h2>Что будем изучать?</h2><p class="muted" id="selected-count" aria-live="polite">Выбрано: <?= count($selection) ?></p></div>
        <div class="topics"><?php foreach ($topics as $topic): ?><label class="topic"><input type="checkbox" name="topics[]" value="<?= (int) $topic['id'] ?>" <?= in_array((int) $topic['id'], $selection, true) ? 'checked' : '' ?>><span><strong><?= e($topic['title']) ?></strong><small><?= e(category($topic['category'])) ?> · <?= (int) $topic['read_minutes'] + (int) $topic['practice_minutes'] ?> мин с практикой</small></span></label><?php endforeach; ?></div>
        <div class="form-grid"><label>Минут в день<input type="number" name="daily_minutes" min="15" max="240" step="1" required value="<?= e($daily === false ? 45 : $daily) ?>"></label><label>Количество прохождений<select name="repetitions"><?php foreach ([1 => 'Один раз', 2 => 'Два раза', 3 => 'Три раза'] as $number => $label): ?><option value="<?= $number ?>" <?= $number === $repetitions ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></label></div>
        <div><button class="button">Рассчитать план</button></div>
    </form>
    <aside class="panel plan-result" id="result" aria-live="polite"><p class="eyebrow">Твой маршрут</p>
    <?php if ($result): ?><div class="result-number"><?= $result['days'] ?></div><h2><?= wordForm($result['days'], ['день', 'дня', 'дней']) ?> подготовки</h2><dl><div><dt>Материалов</dt><dd><?= $result['count'] ?></dd></div><div><dt>Всего времени</dt><dd><?= $result['minutes'] ?> мин</dd></div><div><dt>В день</dt><dd><?= (int) $daily ?> мин</dd></div><div><dt>Прохождений</dt><dd><?= (int) $repetitions ?></dd></div></dl><p class="formula">Сумма времени чтения и практики × количество прохождений ÷ минуты в день. Результат округляется вверх.</p>
    <?php else: ?><span class="plan-icon" aria-hidden="true">&lt;/&gt;</span><h2>Понемногу,<br> но каждый день</h2><p>Отметь интересующие темы. Здесь появится расчёт твоего плана.</p><p class="formula">Оценка ориентировочная: сложную тему можно повторить, а на практику оставить дополнительный день.</p><?php endif; ?>
    </aside>
</div>
