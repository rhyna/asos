<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . '/include/header.php';

require_once __DIR__ . '/search.php';

$error = null;

try {
    Auth::ifNotLoggedIn();

    $categoryLevels = Category::getCategoryLevels($conn);

    $allBrands = Brand::getAllBrands($conn);

    $product = new Product();

    $sizeIds = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product->fillProductObject($_POST);

        if ($product->createProduct($conn, $_FILES)) {
            if ($product->updateProductImage($conn, $_FILES)) {
                if ($product->updateProductSizes($conn)) {
                    //Url::redirect("/admin/products.php");
                }
            }

            $searchWords = $product->getSearchDataForProduct($conn);

            $wordsByProduct = prepareSearchWordsByProduct($searchWords);

            createSearchWordsForProduct($conn, $wordsByProduct);
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
