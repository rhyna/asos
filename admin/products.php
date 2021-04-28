<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . '/include/header.php';

$error = null;

$data = parse_url($_SERVER['REQUEST_URI']);

$brandsData = [];

$categories = [];

require_once __DIR__ . '/../include/selectpicker.php';

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

$pageOfProducts = [];

try {
    Auth::ifNotLoggedIn();

    $categoriesData = require_once __DIR__ . '/../include/categories-config.php';

    $categories = [];

    foreach ($categoriesData as $gender) {
        $flag = [];

        $flag = $gender['flag'];

        $categories[$flag] = [];

        foreach ($gender['categories'] as $key => $level1) {
            foreach ($level1 as $level2) {
                $level2Array = [];

                $level2Array['id'] = $level2['id'];

                $level2Array['title'] = $level2['title'];

                $level2Array['parentCategoryTitle'] = $key;

                $categories[$flag][] = $level2Array;
            }
        }
    }

    $entityType = 'product';

    $productQueryParameters = [];

    $whereClauses = [];

    $order = '';

    if (isset($_GET['categories'])) {
        $categoryIds = $_GET['categories'];

        $productQueryParameters['categoryIds'] = $categoryIds;

        $whereClauses[] = 'FIND_IN_SET(p.category_id, :categoryIds)';

    }

    if (isset($_GET['brands'])) {
        $brandIds = $_GET['brands'];

        $productQueryParameters['brandIds'] = $brandIds;

        $whereClauses[] = 'FIND_IN_SET(p.brand_id, :brandIds)';
    }

    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === 'price-asc') {
            $order = 'order by min(p.price) asc';
        }

        if ($_GET['sort'] === 'price-desc') {
            $order = 'order by min(p.price) desc';
        }
    }

    $select = 'p.*, b.title as brand_title, c.title as category_title';

    $where = 'where ' . implode(' and ', $whereClauses);

    if (!$whereClauses) {
        $where = '';
    }

    $join = 'left join brand b on b.id = p.brand_id join category c on c.id = p.category_id';

    $totalProducts = Product::countProductsFiltered($conn, $productQueryParameters, $join, $where);

    $paginator = new Paginator($page, 10, $totalProducts);

    $pageOfProducts = Product::getPageOfProductsFiltered($conn, $select, $productQueryParameters, $join, $where, $order, $paginator->limit, $paginator->offset);

    $brands = Brand::getAllBrands($conn);

    foreach ($brands as $item) {
        $data = [];

        $data['id'] = $item->id;
        $data['title'] = $item->title;

        $brandsData[] = $data;
    }

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<main>
    <div class="container">
        <?php if ($error): ?>
            <div><?= $error ?></div>
        <?php else: ?>
            <a href="/admin/add-product.php" class="add-entity">Add product</a>
            <a href="/admin/sizes.php" class="add-entity">Manage sizes</a>
            <h1 class="entity-list-title">Products</h1>
            <div class="catalog-filters">
                <form>
                    <?php
                    renderSortSelectPicker();

                    renderSelectPicker($brandsData, 'brands', 'Brand', []);

                    foreach ($categories as $gender => $category) {
                        $settings = [
                            'optGroups' => $category
                        ];

                        renderSelectPicker($category, 'categories', $gender . ' categories', $settings);
                    }

                    ?>
                    <button type="submit" class="catalog-filters-submit">Filter</button>
                </form>
            </div>
            <?php if ($pageOfProducts): ?>
                <div class="entity-list entity-list--product">
                    <div class="entity-list-header">
                        <div class="row">
                            <div class="col"></div>
                            <div class="col-3">Title</div>
                            <div class="col">Product Code</div>
                            <div class="col">Price</div>
                            <div class="col">Brand</div>
                            <div class="col">Category</div>
                            <div class="col-1"></div>
                        </div>
                    </div>
                    <div class="entity-list-content">
                        <?php foreach ($pageOfProducts as $product) : ?>
                            <div class="entity-list-item__wrapper">
                                <div class="entity-list-item">
                                    <div class="row entity-list-item__row">
                                        <div class="col">
                                            <div class="entity-list-item-image"
                                                 style="background-image: url('<?= $product->image ?>')">
                                                <a href="/admin/edit-product.php?id=<?= $product->id ?>"></a>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="/product.php?id=<?= $product->id ?>">
                                                <?= $product->title ?>
                                            </a>
                                        </div>
                                        <div class="col"><?= $product->product_code ?></div>
                                        <div class="col"><?= number_format($product->price, 2, '.', ' ') ?></div>
                                        <div class="col"><?= $product->brand_title ?></div>
                                        <div class="col"><?= $product->category_title ?></div>
                                        <div class="col-1 entity-list-item-icons">
                                            <div class="entity-list-item-icons__inner">
                                                <a href="/admin/edit-product.php?id=<?= $product->id ?>">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <button type="button" data-toggle="modal"
                                                        data-target="#deleteEntity"
                                                        onclick="passEntityId(<?= $product->id ?>)">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p class="catalog-no-products">No products matching the selected criteria</p>
            <?php endif; ?>
        <?php endif; ?>
        <?php
        if ($pageOfProducts) {
            require_once __DIR__ . '/../include/pagination.php';
        }
        ?>
    </div>
</main>

<?php
if (!$error) {
    require_once __DIR__ . '/include/delete-entity-confirmation.php';

    require_once __DIR__ . '/include/on-entity-deletion-modal.php';
}
?>

<?php require_once __DIR__ . '/include/footer.php'; ?>
