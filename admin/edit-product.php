<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

$mode = 'edit-product';

$error = null;

try {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new BadRequestException('The id is not provided');
    }

    $id = (int)$id;

    $product = Product::getProduct($conn, $id) ?? null;

    if (!$product) {
        throw new NotFoundException('Such a product does not exist');
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

} catch (BadRequestException $e) {
    $error = $e->getMessage();

} catch (NotFoundException $e) {
    $error = $e->getMessage();

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<main>
    <div class="container">
        <div class="edit-product-page">
            <div class="admin-title">
                Edit product
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


