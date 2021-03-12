<?php
$currentURL = $_SERVER['REQUEST_URI'];

$baseUrl = strtok($currentURL, $token);
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