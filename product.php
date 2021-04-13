<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

$error = '';

$breadCrumbsData = [];

try {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new Exception('No product id provided');
    }

    $id = (int)$id;

    $product = Product::getProduct($conn, $id);

    if (!$product) {
        throw new Exception('Such a product does not exist');
    }

    $productSizes = $product->getProductSizes($conn);

    $product->sizes = $productSizes;

    $categoryId = $product->category_id;

    $category = Category::getCategory($conn, $categoryId);

    $categoryTitle = $category->title;

    $productTitle = $product->title;

    $rootCategoryFlag = require_once __DIR__ . '/include/root-category-flag.php';

    $breadCrumbsData = [
        [
            'title' => $rootCategoryFlag,
            'url' => "/$rootCategoryFlag.php",

        ],
        [
            'title' => $categoryTitle,
            'url' => "/category.php/?id=$categoryId",
        ],
        [
            'title' => $productTitle,
            'url' => '',
        ],
    ];

    include_once __DIR__ . '/include/breadcrumbs.php';

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

?>

<?php if ($error): ?>
    <p class="error-message"><?= $error ?></p>
<?php else: ?>
    <main class="main-content">
        <?php renderBreadcrumbs($breadCrumbsData); ?>
    </main>
<?php endif; ?>

<?php require_once __DIR__ . '/include/footer.php'; ?>


