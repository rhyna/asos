<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

$error = null;

$pageOfBrands = [];

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

try {
    Auth::ifNotLoggedIn();

    $entityType = 'brand';

    $totalBrands = Brand::countBrands($conn);

    $paginator = new Paginator($page, 10, $totalBrands);

    $pageOfBrands = Brand::getBrandPage($conn, $paginator->limit, $paginator->offset);

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

?>

<main>
    <div class="container">
        <?php if ($error): ?>
            <div><?= $error ?></div>
        <?php else: ?>
            <a href="/admin/add-brand.php" class="add-entity">Add brand</a>
            <h1 class="entity-list-title">Brands</h1>
            <div class="entity-list entity-list--brand">
                <div class="entity-list-header">
                    <div class="row">
                        <div class="col">Title</div>
                        <div class="col-1"></div>
                    </div>
                </div>
                <div class="entity-list-content">
                    <?php foreach ($pageOfBrands as $brand) : ?>
                    <div class="entity-list-item__wrapper">
                        <div class="entity-list-item">
                            <div class="row entity-list-item__row">
                                <div class="col">
                                    <a href="/admin/edit-brand.php?id=<?= $brand->id ?>">
                                        <?= $brand->title ?>
                                    </a>
                                </div>
                                <div class="col-1 entity-list-item-icons">
                                    <div class="entity-list-item-icons__inner">
                                        <a href="/admin/edit-brand.php?id=<?= $brand->id ?>">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <button type="button" data-toggle="modal"
                                                data-target="#deleteEntity"
                                                onclick="passEntityId(<?= $brand->id ?>)">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php
        if ($pageOfBrands) {
            require_once __DIR__ . '/../include/pagination.php';
        }
        ?>
    </div>
</main>

<?php
if (!$error) {
    require_once __DIR__ . '/include/delete-entity-confirmation.php';

    require_once __DIR__ . '/include/on-entity-deletion-modal.php';
}
?>

<?php require_once __DIR__ . '/include/footer.php'; ?>
