<?php

/**
 * @var $conn ;
 */

require_once __DIR__ . "/include/header.php";

$error = '';

$pageOfProductsFound = [];

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

try {
    $query = $_GET['query'] ?? null;

    if (!$query) {
        throw new Exception('No search query provided');
    }

    $normalizedQuery = Search::normalizeString($query);

    $normalizedQuery = array_unique($normalizedQuery);

    $wordIdArray = SearchWord::getSearchWords($conn, $normalizedQuery);

    $wordIds = [];

    foreach ($wordIdArray as $item) {
        $wordIds[] = $item['id'];
    }

    $searchResult = [];

    if (count($normalizedQuery) === count($wordIds)) {
        $searchResult = Product::getProductsBySearchWords($conn, $wordIds);
    }

    if ($searchResult) {
        $productIds = [];

        foreach ($searchResult as $product) {
            $productIds[] = $product->id;
        }

        $productQueryParameters['productIds'] = $productIds;

        $where = 'where FIND_IN_SET(p.id, :productIds)';

        $totalProductsFound = Product::countProductsFiltered($conn, $productQueryParameters, '', $where);

        $paginator = new Paginator($page, 12, $totalProductsFound);

        $pageOfProductsFound = Product::getPageOfProductsBySearchWords($conn, $wordIds, $paginator->limit, $paginator->offset);
    }

} catch (Throwable $e) {
    $error = $e->getMessage();
}

if ($error): ?>
    <p class="error-message"><?= $error ?></p>
<?php else: ?>
    <main class="main-content">
        <div class="search-query">
            <div class="search-query-title">You searched:</div>
            <div class="search-query-text">
                <?= $query ?>
            </div>
            <div class="search-query-divider"></div>
        </div>
        <div class="catalog">
            <div class="row">
                <?php if ($pageOfProductsFound): ?>
                    <?php foreach ($pageOfProductsFound as $product): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="catalog-item">
                                <a href="/product.php?id=<?= $product->id ?>">
                                    <?php if ($product->image): ?>
                                        <div class="catalog-item-image"
                                             style="background-image: url('<?= $product->image ?>')">
                                        </div>
                                    <?php else: ?>
                                        <div class="catalog-item-image catalog-item-image--noimage">
                                            No image
                                        </div>
                                    <?php endif; ?>

                                    <div class="catalog-item-title">
                                        <?= $product->title ?>
                                    </div>
                                    <div class="catalog-item-price">
                                        <span>€</span>
                                        <?= number_format($product->price, 2, '.', ' ') ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col">
                        <p class="catalog-no-products">No products matching the search query</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
<?php endif; ?>

<?php
if ($pageOfProductsFound) {
    require_once __DIR__ . '/include/pagination.php';
}

require_once __DIR__ . "/include/footer.php";
?>


