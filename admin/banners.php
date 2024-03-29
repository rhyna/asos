<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

$error = null;

$pageOfBanners = [];

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

try {
    Auth::ifNotLoggedIn();

    $entityType = 'banner';

    $totalBanners = Banner::countBanners($conn);

    $paginator = new Paginator($page, 10, $totalBanners);

    $pageOfBanners = Banner::getBannerPage($conn, $paginator->limit, $paginator->offset);

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
            <a href="/admin/add-banner.php" class="add-entity">Add banner</a>
            <h1 class="entity-list-title">Banners</h1>
            <div class="entity-list entity-list--banner">
                <div class="entity-list-header">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col">Banner Place</div>
                        <div class="col">Title</div>
                        <div class="col">Link</div>
                        <div class="col-1"></div>
                    </div>
                </div>
                <div class="entity-list-content">
                    <?php foreach ($pageOfBanners as $banner) : ?>
                        <div class="entity-list-item__wrapper">
                            <div class="entity-list-item">
                                <div class="row entity-list-item__row">
                                    <div class="col-3">
                                        <div class="entity-list-item-image"
                                             style="background-image: url('<?= $banner->image ?>')">
                                            <a href="/admin/edit-banner.php?id=<?= $banner->id ?>"></a>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <a href="/admin/edit-banner.php?id=<?= $banner->id ?>">
                                            <?= $banner->aliasTitle ?: 'NO PLACE (Banner not posted yet)' ?>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <?= $banner->title ?>
                                    </div>
                                    <div class="col">
                                        <?= $banner->link ?>
                                    </div>
                                    <div class="col-1 entity-list-item-icons">
                                        <div class="entity-list-item-icons__inner">
                                            <a href="/admin/edit-banner.php?id=<?= $banner->id ?>">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button type="button" data-toggle="modal"
                                                    data-target="#deleteEntity"
                                                    onclick="passEntityId(<?= $banner->id ?>)">
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
        if ($pageOfBanners) {
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
