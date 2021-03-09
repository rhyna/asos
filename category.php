<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

$error = '';

$currentCategory = null;

$productsByCategory = [];

try {
    $categoryId = $_GET['id'] ?? null;

    if (!$categoryId) {
        throw new Exception('No category id provided');
    }

    $categoryId = (int)$categoryId;

    $currentCategory = Category::getCategory($conn, $categoryId);

    if (!$currentCategory) {
        throw new Exception('Such a category does not exist');
    }

    $parentCategoryId = Category::getParentCategory($conn, $categoryId);

    $rootCategoryId = Category::getParentCategory($conn, $parentCategoryId);

    $rootCategory = Category::getCategory($conn, $rootCategoryId);

    $rootCategoryFlag = '';

    if ((int)$rootCategory->rootWomenCategory === 1) {
        $rootCategoryFlag = 'women';
    }

    if ((int)$rootCategory->rootMenCategory === 1) {
        $rootCategoryFlag = 'men';
    }

    $productsByCategory = Product::getProductsByCategory($conn, $categoryId);

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

?>

<main class="main-content-catalog">
    <div class="category-info">
        <h1 class="category-info-title">
            <?= $currentCategory->title ?>
        </h1>
        <div class="category-info-description">
            <?= $currentCategory->description ?>
        </div>
    </div>
    <div class="catalog">
        <div class="row">
            <?php foreach ($productsByCategory as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="catalog-item">
                        <div class="catalog-item-image"
                             style="background-image: url('<?= $product->image ?>')">
                        </div>
                        <div class="catalog-item-title">
                            <?= $product->title ?>
                        </div>
                        <div class="catalog-item-price">
                            <span>€</span>
                            <?= $product->price ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<p><?= $error ?></p>

<?php require_once __DIR__ . '/include/footer.php'; ?>



