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
                    <div class="col">Title</div>
                    <div class="col">Product Code</div>
                    <div class="col">Price</div>
                    <div class="col">Brand</div>
                    <div class="col">Category</div>
                </div>
            </div>
            <div class="allproducts-list-content">
                <?php foreach ($allProducts as $product) : ?>
                    <div class="allproducts-list-item">
                        <a href="/product.php?id=<?= $product->id ?>">
                            <div class="row">
                                <div class="col">
                                    <div class="allproducts-list-item--image"
                                         style="background-image: url('<?= $product->image ?>')"></div>
                                </div>
                                <div class="col"><?= $product->title ?></div>
                                <div class="col"><?= $product->product_code ?></div>
                                <div class="col"><?= $product->price ?></div>
                                <div class="col"><?= $product->brand_title ?></div>
                                <div class="col"><?= $product->category_title ?></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
