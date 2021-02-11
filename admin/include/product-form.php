<?php

$images = $product->getImagesArray();

?>

<?php if ($product->productErrors): ?>
    <ul class="entity-form-errors">
        <?php foreach ($product->productErrors as $fileName => $productError): ?>
            <li><?= $productError ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php if ($product->imageErrors): ?>
    <ul class="entity-form-errors entity-form-errors--image">
        <?php foreach ($product->imageErrors as $imageName => $imageErrorArray): ?>
            <li class="entity-form-errors--image-group"><?= $imageName ?>:
                <ul>
                    <?php foreach ($imageErrorArray as $imageError): ?>
                        <li><?= $imageError ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


<form action="" method="post" enctype="multipart/form-data" id="productForm" class="entity-form product-form">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title" id="title"
               value="<?= htmlspecialchars($product->title) ?>">
    </div>
    <div class="form-group">
        <label for="productCode">Product Code</label>
        <input class="form-control" type="text" name="productCode" id="productCode"
               value="<?= htmlspecialchars($product->product_code) ?>" <?= $mode === 'edit-product' ? 'readonly' : '' ?>>
    </div>
    <div class="form-group">
        <label for="price">Price, €</label>
        <input class="form-control" type="number" step='any' min='0.01' name="price" id="price"
               value="<?= htmlspecialchars($product->price) ?>">
    </div>
    <div class="form-group">
        <label for="productDetails">Details</label>
        <textarea class="form-control" name="productDetails" id="productDetails"
                  rows="5"><?= htmlspecialchars($product->product_details) ?></textarea>
    </div>
    <div class="form-group">
        <label for="categoryId">Category</label>
        <select class="form-control" id="categoryId" name="categoryId"
                onchange="showSizes()">
            <?php foreach ($categoryLevels as $categoryLevels1): ?>
                <option class="entity-form-option--disabled" value=""
                        style=" font-weight: 600;
                        text-transform: uppercase">
                    <?= htmlspecialchars($categoryLevels1['title']) ?>
                </option>
                <?php foreach ($categoryLevels1['child_category1'] as $categoryLevels2): ?>
                    <option class="entity-form-option--disabled"
                            value=""
                            style="font-weight: 600">
                        -- <?= htmlspecialchars($categoryLevels2['title']) ?>
                    </option>
                    <?php foreach ($categoryLevels2['child_category2'] as $categoryLevels3): ?>
                        <option value="<?= $categoryLevels3['id'] ?>"
                            <?php if ($categoryLevels3['id'] === $product->category_id): ?>
                                selected="selected"
                            <?php endif; ?>>
                            ---- <?= htmlspecialchars($categoryLevels3['title']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group product-size-list">
        <?php
        if (!isset($sizeIds)) {
            $sizeIds = [];
        }

        echo '<input type="hidden" id="productSizes" value="' . json_encode($sizeIds) . '">';
        ?>
        <label>Select sizes</label>
        <div class="product-size-list-empty">
            Please select a category first
        </div>
        <div class="product-size-list__content">
<!--            content uploaded via JS-->
        </div>
    </div>
    <div class="manage-sizes">
        <a class="manage-sizes-link" href="/admin/sizes.php">Manage sizes</a>
    </div>
    <div class="form-group">
        <label for="brand">Brand</label>
        <select class="form-control" id="brand" name="brandId">
            <option value="">NO BRAND</option>
            <?php foreach ($allBrands as $brand): ?>
                <option value="<?= $brand->id ?>"
                    <?php if ($brand->id === $product->brand_id): ?>
                        selected="selected"
                    <?php endif; ?>>
                    <?= htmlspecialchars($brand->title) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="add-brand" data-toggle="modal" data-target="#addBrand">
            + new brand
        </button>
    </div>
    <div class="form-group">
        <label for="lookAfterMe">Look After Me</label>
        <textarea class="form-control" name="lookAfterMe" id="lookAfterMe"
                  rows="5"><?= htmlspecialchars($product->look_after_me) ?></textarea>
    </div>
    <div class="form-group">
        <label for="aboutMe">About Me</label>
        <textarea class="form-control" name="aboutMe" id="aboutMe"
                  rows="5"><?= htmlspecialchars($product->about_me) ?></textarea>
    </div>
    <div class="row">
        <?php foreach ($images as $imageAlias => $imageArray): ?>
            <?php foreach ($imageArray as $imageName => $imageUrl): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="form-image">
                        <div class="form-group">
                            <div class="entity-form-image <?= $imageUrl ? '' : 'entity-form-image--deleted' ?>"
                                <?= ($imageUrl ? 'style="background-image: url(' . $imageUrl . ')"' : null) ?>>
                                <?= !$imageUrl ? 'No image' : null ?>
                                <?php if ($imageUrl): ?>
                                    <button type="button"
                                            class="entity-form-delete-image-button"
                                            name="delete-image"
                                            data-id="<?= $product->id ?>"
                                            data-image="<?= $imageName ?>"
                                            data-type="product"
                                            onclick="deleteEntityImage(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <input class="form-control-file" name="<?= $imageAlias ?>" id="<?= $imageAlias ?>"
                                   type="file">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <button type="submit" class="entity-form-submit">Submit</button>
</form>