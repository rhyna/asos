<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

$brands = Brand::getAllBrands($conn);

?>

<main>
    <div class="container">
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
                <?php foreach ($brands as $brand) : ?>
                    <div class="entity-list-item">
                        <div class="row">
                            <div class="col">
                                <a href="/admin/edit-brand.php?id=<?= $brand->id ?>">
                                    <?= $brand->title ?>
                                </a>
                            </div>
                            <div class="col-1 entity-list-item-icons">
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
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
