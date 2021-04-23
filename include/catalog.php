<?php
/**
 * @var string $error
 * @var array $breadCrumbsData
 * @var string $rootCategoryFlag
 * @var string $catalogEntityType
 * @var Brand|Category $catalogEntity
 * @var int $entityId
 * @var array $categoriesByBrandAndGender
 * @var array $brandsData
 * @var array $sizesData
 * @var array $productsByEntity
 */
?>

<?php if ($error): ?>
    <p class="error-message"><?= $error ?></p>
<?php else: ?>
    <main class="main-content">
        <?php renderBreadcrumbs($breadCrumbsData); ?>
        <div class="catalog-info__wrapper">
            <div class="catalog-info">
                <h1 class="catalog-info-title">
                    <?= $rootCategoryFlag ?>
                    <?= $catalogEntity->title ?>
                </h1>
                <div class="catalog-info-description text-collapsible text-collapsible--catalog">
                    <?php
                    $entityDescription = '';

                    if ($catalogEntityType === 'category') {
                        $entityDescription = $catalogEntity->description;
                    }

                    if ($catalogEntityType === 'brand') {
                        if ($rootCategoryFlag === 'women') {
                            $entityDescription = $catalogEntity->descriptionWomen;

                        } elseif ($rootCategoryFlag === 'men') {
                            $entityDescription = $catalogEntity->descriptionMen;
                        }
                    }

                    echo $entityDescription;
                    ?>
                </div>
                <?php if ($entityDescription): ?>
                    <button class="text-collapsible-toggle">View more</button>
                <?php endif; ?>
            </div>
        </div>
        <div class="catalog-filters__wrapper">
            <div class="catalog-filters">
                <form>
                    <?php
                    if ($catalogEntityType === 'brand') {
                        echo '<input type="hidden" name="gender" value="' . $rootCategoryFlag . '">';
                    }

                    echo '<input type="hidden" name="id" value="' . $entityId . '">';

                    renderSortSelectPicker();

                    if ($catalogEntityType === 'brand') {
                        $settings = [
                            'optGroups' => $categoriesByBrandAndGender
                        ];

                        renderSelectPicker($categoriesByBrandAndGender, 'categories', 'Category', $settings);
                    }

                    if ($catalogEntityType === 'category') {
                        renderSelectPicker($brandsData, 'brands', 'Brand', []);
                    }

                    renderSelectPicker($sizesData, 'sizes', 'Size', []);
                    ?>
                    <button type="submit" class="catalog-filters-submit">Filter</button>
                </form>
            </div>
        </div>
        <div class="catalog">
            <div class="row">
                <?php if ($productsByEntity): ?>
                    <?php foreach ($productsByEntity as $product): ?>
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
                                        <?= number_format($product->price, 2, '.', ' ') ?>
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
if ($productsByEntity) {
    require_once __DIR__ . '/pagination.php';
}
?>
