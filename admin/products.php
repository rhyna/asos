<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

$allProducts = Product::getAllProducts($conn);

$entityType = 'product';

?>

<main>
    <div class="container">
        <a href="/admin/add-product.php" class="add-entity">Add product</a>
        <h1 class="entity-list-title">Products</h1>
        <div class="entity-list entity-list--product">
            <div class="entity-list-header">
                <div class="row">
                    <div class="col"></div>
                    <div class="col-3">Title</div>
                    <div class="col">Product Code</div>
                    <div class="col">Price</div>
                    <div class="col">Brand</div>
                    <div class="col">Category</div>
                    <div class="col-1"></div>
                </div>
            </div>
            <div class="entity-list-content">
                <?php foreach ($allProducts as $product) : ?>
                    <div class="entity-list-item">
                        <div class="row">
                            <div class="col">
                                <div class="entity-list-item-image"
                                     style="background-image: url('<?= $product->image ?>')"></div>
                            </div>
                            <div class="col-3">
                                <a href="/product.php?id=<?= $product->id ?>">
                                    <?= $product->title ?>
                                </a>
                            </div>
                            <div class="col"><?= $product->product_code ?></div>
                            <div class="col"><?= $product->price ?></div>
                            <div class="col"><?= $product->brand_title ?></div>
                            <div class="col"><?= $product->category_title ?></div>
                            <div class="col-1 entity-list-item-icons">
                                <a href="/admin/edit-product.php?id=<?= $product->id ?>">
                                    <i class="far fa-edit"></i>
                                </a>
                                <button type="button" data-toggle="modal"
                                        data-target="#deleteEntity"
                                        onclick="passEntityId(<?= $product->id ?>)">
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

<?php require_once __DIR__ . '/include/delete-entity-confirmation.php'; ?>

<?php require_once __DIR__ . '/include/on-entity-deletion-modal.php'; ?>

<?php require_once __DIR__ . '/include/footer.php'; ?>
