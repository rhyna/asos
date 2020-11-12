<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

$mode = 'edit-product';

if (isset($_GET['id'])) {
    $product = Product::getProduct($conn, $_GET['id']);
} else {
    $product = null;
}

$categoryLevels = Category::getCategoryLevels($conn);

$allBrands = Brand::getAllBrands($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $product->fillProductObject($_POST);

    if ($product->updateProduct($conn, $_FILES)) {
        if ($product->updateProductImage($conn, $_FILES)) {
            Url::redirect("/admin/products.php");
        }
    }
}

?>

<main>
    <div class="container">
        <div class="add-product-page">
            <div class="add-product-title">
                Edit product
            </div>
            <?php
            if (!$product) {
                echo 'Such a product doesn\'t exist';
            } else {
                include_once __DIR__ . '/include/product-form.php';
            }
            ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>


