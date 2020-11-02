<?php

require_once __DIR__ . '/include/header.php';

$mode = 'add-product';

$rootCategories = Category::getRootCategories($conn);

$firstLevelCategories = Category::getFirstLevelCategories($conn);

$secondLevelCategories = Category::getSecondLevelCategories($conn);

$categoryLevels = Category::$categoryLevels;

$allBrands = Brand::getAllBrands($conn);

$product = new Product();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product->fillProductObject($_POST);

    if ($product->createProduct($conn, $_FILES)) {
        if ($product->updateProductImage($conn, $_FILES)) {
            Url::redirect("/admin");
        };
    }
}

?>

<main>
    <div class="container">
        <div class="add-product-page">
            <div class="add-product-title">
                Add product
            </div>
            <?php include_once __DIR__ . '/include/product-form.php' ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
