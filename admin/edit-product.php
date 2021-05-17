<?php

/**
 * @var PDO $conn ;
 */

require_once __DIR__ . '/include/header.php';

$mode = 'edit-product';

$error = null;

try {
    Auth::ifNotLoggedIn();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new Exception('The id is not provided');
    }

    $id = (int)$id;

    $product = Product::getProduct($conn, $id) ?? null;

    if (!$product) {
        throw new Exception('Such a product does not exist');
    }

    $sizes = $product->getProductSizes($conn);

    $sizeIds = [];

    foreach ($sizes as $size) {
        $sizeIds[] = (int)$size['size_id'];
    }

    $categoryLevels = Category::getCategoryLevels($conn);

    $allBrands = Brand::getAllBrands($conn);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product->fillProductObject($_POST);

        if ($product->validateProductAndImage($conn, $_FILES)) {
            $product->updateProduct($conn);

            $product->updateProductImage($conn, $_FILES);

            $product->updateProductSizes($conn);

            $searchWords = $product->getSearchDataForProduct($conn);

            $wordsByProduct = Search::prepareSearchWordsByProduct($searchWords);

            ProductSearchWord::deleteProductSearchWords($conn, (int)$product->id);

            Search::createSearchWordsForProduct($conn, $wordsByProduct);
        }
    }

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
                require_once __DIR__ . '/include/product-form.php';

                require_once __DIR__ . '/include/add-brand-from-product-modal.php';
            }
            ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>


