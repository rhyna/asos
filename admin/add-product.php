<?php

/**
 * @var PDO $conn ;
 */

require_once __DIR__ . '/include/header.php';

$error = null;

try {
    Auth::ifNotLoggedIn();

    $categoryLevels = Category::getCategoryLevels($conn);

    $allBrands = Brand::getAllBrands($conn);

    $product = new Product();

    $sizeIds = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product->fillProductObject($_POST);

        if ($product->validateProductAndImage($conn, $_FILES)) {
            $product->createProduct($conn);

            $product->updateProductImage($conn, $_FILES);

            $product->updateProductSizes($conn);

            $searchWords = $product->getSearchDataForProduct($conn);

            $wordsByProduct = Search::prepareSearchWordsByProduct($searchWords);

            Search::createSearchWordsForProduct($conn, $wordsByProduct);

            Url::redirect("/admin/edit-product.php?id=$product->id");
        }
    }

    $mode = 'add-product';

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

                require_once __DIR__ . '/include/add-brand-from-product-modal.php';
            }
            ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
