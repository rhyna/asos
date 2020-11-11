<?php

require_once __DIR__ . '/include/header.php';

$allProducts = Product::getAllProducts($conn);

?>

<main>
    <div class="container">
        <a href="/admin/add-product.php" class="add-product">Add product</a>
        <h1 class="allproducts-list-title">Products</h1>
        <div class="allproducts-list">
            <div class="allproducts-list-header">
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
            <div class="allproducts-list-content">
                <?php foreach ($allProducts as $product) : ?>
                    <div class="allproducts-list-item">
                        <div class="row">
                            <div class="col">
                                <div class="allproducts-list-item-image"
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
                            <div class="col-1 allproducts-list-item-icons">
                                <a href="/admin/edit-product.php?id=<?= $product->id ?>">
                                    <i class="far fa-edit"></i>
                                </a>
                                <button type="button" data-toggle="modal"
                                        data-target="#deleteProduct"
                                        onclick="passProductId(<?= $product->id ?>)">
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

<div class="modal fade delete-product-modal" id="deleteProduct" tabindex="-1" role="dialog"
     aria-labelledby="deleteProductLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the product?
            </div>
            <form action="/admin/delete-product.php" method="post">
                <input type="hidden" class="delete-product-modal-product-id" value="" name="id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No, close</button>
                    <button type="submit" class="btn btn-primary">Yes, delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
