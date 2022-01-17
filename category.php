<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

require_once __DIR__ . '/include/selectpicker.php';

$error = '';

$currentCategory = new Category();

$productsByCategory = [];

$brandsByCategory = [];

$sizesByCategory = [];

$breadCrumbsData = [];

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

$rootCategoryFlag = '';

$parentCategoryId = 0;

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

    $rootCategoryFlag = require_once __DIR__ . '/include/root-category-flag.php';

    $brandsByCategory = Product::getBrandsByCategory($conn, $categoryId);

    $sizesByCategory = Size::getSizesByCategory($conn, $parentCategoryId);

    $productQueryParameters = [];

    $whereClauses = [];

    $joinClauses = [];

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

        $joinClauses[] = 'join product_size ps on p.id = ps.product_id';
    }

    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === 'price-asc') {
            $order = 'order by min(p.price) asc';
        }

        if ($_GET['sort'] === 'price-desc') {
            $order = 'order by min(p.price) desc';
        }
    }

    $select = 'p.*';

    $where = 'where ' . implode(' and ', $whereClauses);

    $join = implode(' join ', $joinClauses);

    $totalProductsInCategory = Product::countProductsFiltered($conn, $productQueryParameters, $join, $where);

    $paginator = new Paginator($page, 12, $totalProductsInCategory);

    $productsByCategory = Product::getPageOfProductsFiltered($conn, $select, $productQueryParameters, $join, $where, $order, $paginator->limit, $paginator->offset);

    $breadCrumbsData = [
        [
            'title' => $rootCategoryFlag,
            'url' => "/$rootCategoryFlag.php",

        ],
        [
            'title' => $currentCategory->title,
            'url' => "/category.php?id=$categoryId",
        ],
    ];

    include_once __DIR__ . '/include/breadcrumbs.php';

} catch (Throwable $e) {
    $error = $e->getMessage();
}

$catalogEntityType = 'category';

$entityId = $categoryId;

$catalogEntity = $currentCategory;

$productsByEntity = $productsByCategory;

$brandsData = [];

$sizesData = [];

foreach ($brandsByCategory as $item) {
    $data = [];

    $data['id'] = $item->brandId;
    $data['title'] = $item->brandTitle;

    $brandsData[] = $data;
}

foreach ($sizesByCategory as $item) {
    $data = [];

    $data['id'] = $item->id;
    $data['title'] = $item->title;

    $sizesData[] = $data;
}

require_once __DIR__ . '/include/header.php';

require_once  __DIR__ . '/include/catalog.php';

require_once __DIR__ . '/include/footer.php';



