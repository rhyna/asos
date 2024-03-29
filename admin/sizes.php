<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

$entityType = 'size';

$error = null;

$categoryLevels = [];

try {
    Auth::ifNotLoggedIn();

    $categoryLevels = Category::getCategoryLevels($conn);

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

?>

<main>
    <div class="container">
        <?php if ($error): ?>
            <p><?= $error ?></p>
        <?php else: ?>
            <div class="manage-sizes-page">
                <div class="admin-title">
                    Manage sizes
                </div>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="sizeForm" class="entity-form size-form">
                <div class="form-group">
                    <label for="categoryId--sizeList">Select category</label>
                    <select class="form-control" id="categoryId--sizeList" name="categoryId--sizeList"
                            onchange="manageSizes()">
                        <?php foreach ($categoryLevels as $categoryLevels1): ?>
                            <option class="entity-form-option--disabled" value=""
                                    style=" font-weight: 600;
                                    text-transform: uppercase">
                                <?= htmlspecialchars($categoryLevels1['title']) ?>
                            </option>
                            <?php foreach ($categoryLevels1['child_category1'] as $categoryLevels2): ?>
                                <option value="<?= $categoryLevels2['id'] ?>">
                                    -- <?= htmlspecialchars($categoryLevels2['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button"
                        class="admin-button add-size-button"
                        data-toggle="modal"
                        data-target="#addSize">
                    Add size
                </button>
                <div class="form-group product-size-list">
                    <div class="product-size-list-empty product-size-list-empty--manageSizes">
                        Please select a category first
                    </div>
                    <div class="product-size-list__header">
                        <div class="row">
                            <div class="col">Size</div>
                            <div class="col-3">Size sorting order</div>
                            <div class="col-1"></div>
                        </div>
                    </div>
                    <div class="product-size-list__content">
                        <div class="entity-list entity-list--size">
                            <div class="entity-list-content">
                                <!--                                content uploaded via JS-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php

require_once __DIR__ . '/include/add-size-modal.php';

require_once __DIR__ . '/include/edit-size-modal.php';

require_once __DIR__ . '/include/delete-entity-confirmation.php';

require_once __DIR__ . '/include/on-entity-deletion-modal.php';

require_once __DIR__ . '/include/footer.php';

?>
