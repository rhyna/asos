<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

require_once __DIR__ . '/include/filter-select.php';

$error = '';

$currentCategory = null;

$productsByCategory = [];

$brandsByCategory = [];

$sizesByCategory = [];

//if (!isset($_GET['page'])) {
//    Url::redirect($_SERVER['REQUEST_URI'] . '&page=1');
//
//    // redirect also when there are all other bad cases (page = 0/string/invalid int)
//}

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

    $brandsByCategory = Product::getBrandsByCategory($conn, $categoryId);

    $sizesByCategory = Size::getSizesByCategory($conn, $parentCategoryId);

    $productQueryParameters = [];

    $whereClauses = [];

    $order = '';

    $brandIds = [];

    if ($_GET['id']) {
        $productQueryParameters['categoryId'] = $categoryId;

        $whereClauses[] = 'p.category_id = :categoryId';
    }

    if (isset($_GET['brands'])) {
        $brandIds = $_GET['brands'];

        $productQueryParameters['brandIds'] = $brandIds;

        $whereClauses[] = 'FIND_IN_SET(p.brand_id, :brandIds)';
    }

    if (isset($_GET['sizes'])) {
        $sizeIds = $_GET['sizes'];

        $productQueryParameters['sizeIds'] = $sizeIds;

        $whereClauses[] = 'FIND_IN_SET(ps.size_id, :sizeIds)';
    }

    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === 'price-asc') {
            $order = 'order by min(p.price) asc';
        }

        if ($_GET['sort'] === 'price-desc') {
            $order = 'order by min(p.price) desc';
        }
    }

    $where = implode(' and ', $whereClauses);

    $totalProductsInCategory = Product::countProductsFiltered($conn, $productQueryParameters, $where);

    $paginator = new Paginator($page, 2, $totalProductsInCategory);

    $productsByCategory = Product::getPageOfProductsFiltered($conn, $productQueryParameters, $where, $order, $paginator->limit, $paginator->offset);

} catch (Throwable $e) {
    $error = $e->getMessage();
}

$brandsData = [];

foreach ($brandsByCategory as $item) {
    $data = [];

    $data['data']['id'] = $item->brandId;
    $data['data']['title'] = $item->brandTitle;

    $brandsData[] = $data;
}

$sizesData = [];

foreach ($sizesByCategory as $item) {
    $data = [];

    $data['data']['id'] = $item->id;
    $data['data']['title'] = $item->title;

    $sizesData[] = $data;
}

require_once __DIR__ . '/include/header.php';

?>
<?php if ($error): ?>
    <p class="error-message"><?= $error ?></p>
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
                <form>
                    <input type="hidden" name="id" value="<?= $categoryId ?>">
                    <?php
                    renderSortSelectPicker();

                    renderSelectPicker($brandsData, 'brands', 'Brand');

                    renderSelectPicker($sizesData, 'sizes', 'Size');
                    ?>
                    <button type="submit" class="catalog-filters-submit">Filter</button>
                </form>
            </div>
        </div>
        <div class="catalog">
            <div class="row">
                <?php if ($productsByCategory): ?>
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
                <?php else: ?>
                    <div class="col">
                        <p class="catalog-no-products">No products matching the selected criteria</p>
                    </div>
                <?php endif; ?>
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



