<?php

/**
 * @var Paginator $paginator;
 */

$token = '&';

$data = parse_url($_SERVER['REQUEST_URI']);

$path = $data['path'];

$query = '';

if (isset($data['query'])) {
    $query = $data['query'];
}

parse_str($query, $queryArray);

$onlyPageQuery = count($queryArray) === 1 && array_key_exists('page', $queryArray);

if (!$query || $onlyPageQuery) {
    $token = '?';
}

unset($queryArray['page']);

$newQuery = http_build_query($queryArray);

$baseUrl = $path;

if ($newQuery) {
    $baseUrl = $path . '?' . $newQuery;
}

?>

<nav class="pagination-container">
    <ul class="pagination">
        <li class="page-item pagination-item">
            <?php if ($paginator->previous): ?>
                <a class="page-link pagination-link" aria-label="Previous"
                   href="<?= $baseUrl ?><?= $token ?>page=<?= $paginator->previous ?>">Previous</a>
            <?php else: ?>
                <a class="nav-link disabled" aria-label="Previous" href="#" tabindex="-1"
                   aria-disabled="true">Previous</a>
            <?php endif; ?>
        </li>
        <?php for ($i = 1; $i <= $paginator->totalPages; $i++): ?>
            <li class="page-item pagination-item"><a class="page-link pagination-link<?=
                $_GET['page'] == $i ? ' active' : '';
                ?>" href="<?= $baseUrl ?><?= $token ?>page=<?= $i ?>"><?= $i ?></a></li>
        <?php endfor; ?>
        <li class="page-item pagination-item">
            <?php if ($paginator->next): ?>
                <a class="page-link pagination-link" aria-label="Next"
                   href="<?= $baseUrl ?><?= $token ?>page=<?= $paginator->next ?>">Next</a>
            <?php else: ?>
                <a class="nav-link disabled" aria-label="Next" href="#" tabindex="-1" aria-disabled="true">Next</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>
