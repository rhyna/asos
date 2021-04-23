<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

require_once __DIR__ . '/include/selectpicker.php';

$error = '';

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

$brand = new Brand();

$productsByBrand = [];

$categoriesByBrandAndGender = [];

$sizesByCategoryIds = [];

$breadCrumbsData = [];

$rootCategoryFlag = require_once __DIR__ . '/include/root-category-flag.php';

require_once __DIR__ . '/include/header.php';

try {
    if (!isset($_GET['gender'])) {
        throw new Exception('No gender provided');
    }

    if ($_GET['gender'] !== 'men' && $_GET['gender'] !== 'women') {
        throw new Exception('Provide correct gender (men / women)');
    }

    $brandId = $_GET['id'] ?? null;

    if (!$brandId) {
        throw new Exception('No brand id provided');
    }

    $brandId = (int)$brandId;

    $brand = Brand::getBrand($conn, $brandId);

    if (!$brand) {
        throw new Exception('Such a brand does not exist');
    }

    $productQueryParameters = [];

    $whereClauses = [];

    $joinClauses = [];

    $order = '';

    $config = require __DIR__ . "/include/categories-config.php";

    $targetNode = [];

    $categoriesByGenderData = [];

    foreach ($config as $configData) {
        if ($rootCategoryFlag === $configData['flag']) {
            $targetNode = $configData;

            break;
        }
    }

    foreach ($targetNode['categories'] as $key => $firstLevelCategories) {
        $parentCategoryTitlesByGender[] = $firstLevelCategories;

        foreach ($firstLevelCategories as $secondLevelCategories) {
            $data = [];

            $data['parentCategory'] = $key;

            $data['parentId'] = $secondLevelCategories['parent_id'];

            $data['id'] = $secondLevelCategories['id'];

            $categoriesByGenderData[] = $data;
        }
    }

    $categoryIdsByGender = [];

    foreach ($categoriesByGenderData as $item) {
        $categoryIdsByGender[] = $item['id'];
    }

    $parentCategoryIdsByGender = [];

    foreach ($categoriesByGenderData as $item) {
        $parentCategoryIdsByGender[] = $item['parentId'];
    }

    $parentCategoryIdsByGender = array_unique($parentCategoryIdsByGender);

    $categoriesByBrandAndGender = Product::getCategoriesByBrandAndGender($conn, $brandId, $categoryIdsByGender);

    $sizesByCategoryIds = Size::getSizesByCategoryArray($conn, $parentCategoryIdsByGender);

    $productQueryParameters['categoryIdsByGender'] = $categoryIdsByGender;

    $whereClauses[] = 'FIND_IN_SET(p.category_id, :categoryIdsByGender)';

    $productQueryParameters['brandId'] = $brandId;

    $whereClauses[] = 'p.brand_id = :brandId';

    if (isset($_GET['categories'])) {
        $categoryIds = $_GET['categories'];

        $productQueryParameters['categoryIds'] = $categoryIds;

        $whereClauses[] = 'FIND_IN_SET(p.category_id, :categoryIds)';

    }

    if (isset($_GET['sizes'])) {
        $sizeIds = $_GET['sizes'];

        $productQueryParameters['sizeIds'] = $sizeIds;

        $joinClauses[] = 'join product_size ps on p.id = ps.product_id';

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

    $select = 'p.*';

    $where = 'where ' . implode(' and ', $whereClauses);

    $join = implode(' join ', $joinClauses);

    $totalProductsByBrand = Product::countProductsFiltered($conn, $productQueryParameters, $join, $where);

    $paginator = new Paginator($page, 2, $totalProductsByBrand);

    $productsByBrand = Product::getPageOfProductsFiltered($conn, $select, $productQueryParameters, $join, $where, $order, $paginator->limit, $paginator->offset);

    $breadCrumbsData = [
        [
            'title' => $rootCategoryFlag,
            'url' => "/$rootCategoryFlag.php",

        ],
        [
            'title' => "All $rootCategoryFlag Brands",
            'url' => "/brands.php?gender=$rootCategoryFlag",
        ],
        [
            'title' => $brand->title,
            'url' => "/brand.php?gender=$rootCategoryFlag&id=$brandId",
        ],
    ];

    include_once __DIR__ . '/include/breadcrumbs.php';

} catch (Throwable $e) {
    $error = $e->getMessage();
}

$catalogEntityType = 'brand';

$catalogEntity = $brand;

$entityId = $brand->id;

$productsByEntity = $productsByBrand;

$sizesData = $sizesByCategoryIds;

require_once __DIR__ . '/include/catalog.php';

require_once __DIR__ . '/include/footer.php';

