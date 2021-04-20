<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

require_once __DIR__ . '/include/selectpicker.php';

$error = '';

$breadCrumbsData = [];

$product = new Product();

$productImages = [];

$sizesData = [];

$brandDescription = '';

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

    foreach ($product->sizes as $size) {
        $data = [];

        $data['id'] = $size['size_id'];

        $data['title'] = $size['size_title'];

        $sizesData[] = $data;
    }

    $categoryId = $product->category_id;

    $category = Category::getCategory($conn, $categoryId);

    $categoryTitle = $category->title;

    $productTitle = $product->title;

    $productImages = [
        $product->image,
        $product->image_1,
        $product->image_2,
        $product->image_3
    ];

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

    $brand = Brand::getBrand($conn, $product->brand_id);

    if ($rootCategoryFlag === 'men') {
        $brandDescription = $brand->descriptionMen;
    } elseif ($rootCategoryFlag === 'women') {
        $brandDescription = $brand->descriptionWomen;
    }

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
        <div class="product">
            <div class="product-top">
                <div class="product-gallery">
                    <?php if ($productImages): ?>
                        <div class="previews">
                            <?php foreach ($productImages as $i => $image): ?>
                                <?php if ($image): ?>
                                    <span class="
                                <?php if ($i === 0): ?>
                                    selected
                                <?php endif; ?>
                                " data-full="<?= $image ?>">
                                    <img src="<?= $image ?>"/>
                                </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="full">
                        <?php foreach ($productImages as $image): ?>
                            <?php if ($image): ?>
                                <img src="<?= $image ?>"/>
                                <?php break; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$productImages): ?>
                            <div class="no-image">
                                No image
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="product-top-info">
                    <h1 class="product-title"><?= $product->title ?></h1>
                    <div class="product-price"><span>€</span><?= number_format($product->price, 2, '.', ' ') ?></div>
                    <div class="product-sizes">
                        <label for="sizes">Size</label>
                        <select class="form-control" name="sizes" id="sizes">
                            <?php foreach ($product->sizes as $size): ?>
                                <option value="<?= $size['size_id'] ?>">
                                    <?= $size['size_title'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="product-bottom text-collapsible text-collapsible--product">
                <div class="row">
                    <div class="product-bottom-col col">
                        <div class="product-bottom-col-info">
                            <h2 class="product-bottom-title">Product details</h2>
                            <?= html_entity_decode($product->product_details) ?>
                        </div>
                    </div>
                    <div class="product-bottom-col col">
                        <div class="product-bottom-col-info">
                            <h2 class="product-bottom-title">Product code</h2>
                            <?= html_entity_decode($product->product_code) ?>
                        </div>
                        <div class="product-bottom-col-info">
                            <h2 class="product-bottom-title">Brand</h2>
                            <?= html_entity_decode($brandDescription) ?>
                        </div>
                    </div>
                    <div class="product-bottom-col col">
                        <div class="product-bottom-col-info">
                            <h2 class="product-bottom-title">Look after me</h2>
                            <?= html_entity_decode($product->look_after_me) ?>
                        </div>
                        <div class="product-bottom-col-info">
                            <h2 class="product-bottom-title">About me</h2>
                            <?= html_entity_decode($product->about_me) ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="text-collapsible-toggle__wrapper">
                <button class="text-collapsible-toggle text-collapsible-toggle--product">View more</button>
            </div>
        </div>
    </main>
<?php endif; ?>
<?php require_once __DIR__ . '/include/footer.php'; ?>


