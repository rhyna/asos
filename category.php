<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

if (!isset($_GET['page'])) {
    Url::redirect($_SERVER['REQUEST_URI'] . '&page=1');

    // redirect also when there are all other bad cases (page = 0/string/invalid int)
}

$error = '';

$currentCategory = null;

$productsByCategory = [];

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

$token = '&';

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

    $totalProductsInCategory = Product::countProductsByCategory($conn, $categoryId);

    $paginator = new Paginator($page, 10, $totalProductsInCategory);

    $productsByCategory = Product::getPageOfProductsByCategory($conn, $categoryId, $paginator->limit, $paginator->offset);

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

?>
<?php if ($error): ?>
    <p><?= $error ?></p>
<?php else: ?>
    <main class="main-content">
        <div class="category-info__wrapper">
            <div class="category-info">
                <h1 class="category-info-title">
                    <?= $currentCategory->title ?>
                </h1>
                <div class="category-info-description">
                    <?= $currentCategory->description ?>
                </div>
            </div>
        </div>
        <div class="catalog-filters__wrapper">
            <div class="catalog-filters">
                <form action="">
                    <select class="selectpicker" multiple data-live-search="true">
                        <option>Mustard</option>
                        <option>Ketchup</option>
                        <option>Relish</option>
                        <option>Mustard1</option>
                        <option>Ketchup1</option>
                        <option>Relish1</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="catalog">
            <div class="row">
                <?php foreach ($productsByCategory as $product): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="catalog-item">
                            <a href="/product.php?id=<?= $product->id ?>">
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
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
<?php endif; ?>

<?php
if ($productsByCategory) {
    require_once __DIR__ . '/include/pagination.php';
}
?>


<?php require_once __DIR__ . '/include/footer.php'; ?>



