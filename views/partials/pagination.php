<?php if ($listing['pages'] > 1): ?>
<nav class="pagination" aria-label="Страницы материалов">
    <?php for ($pageNumber = 1; $pageNumber <= $listing['pages']; $pageNumber++): ?>
        <?php $queryParams = ['q' => $q ?? '', 'category' => $category ?? '', 'sort' => $sort ?? 'newest', 'page' => $pageNumber]; ?>
        <a <?= $pageNumber === $listing['page'] ? 'aria-current="page"' : '' ?> href="<?= e(url($paginationPath ?? '/materials') . '?' . http_build_query($queryParams)) ?>"><?= $pageNumber ?></a>
    <?php endfor; ?>
</nav>
<?php endif; ?>
