<?php

require_once __DIR__ . '/include/header.php';

$error = null;

try {
    Auth::ifNotLoggedIn();

    $categoryLevels = Category::getCategoryLevels($conn);

    $allBrands = Brand::getAllBrands($conn);

    $product = new Product();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product->fillProductObject($_POST);

        if ($product->createProduct($conn, $_FILES)) {
            if ($product->updateProductImage($conn, $_FILES)) {
                Url::redirect("/admin/products.php");
            };
        }
    }

    $mode = '';

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<main>
    <div class="container">
        <div class="add-product-page">
            <div class="admin-title">
                Add product
            </div>
            <?php
            if ($error) {
                echo $error;
            } else {
                include_once __DIR__ . '/include/product-form.php';
            }
            ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
