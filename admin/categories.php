<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

$error = null;

$pageOfCategories = [];

$categoriesByGender = [];

require_once __DIR__ . '/../include/selectpicker.php';

if (!isset($_GET['page']) || (string)(int)$_GET['page'] !== $_GET['page']) {
    $_GET['page'] = 1;
}

$page = (int)$_GET['page'];

try {
    Auth::ifNotLoggedIn();

    $categories = Category::getCategoryLevels($conn);

    $categoriesByGender = [];

    foreach ($categories as $gender) {
        foreach ($gender['child_category1'] as $category) {
            $data = [];

            $data['id'] = $category['id'];

            $data['title'] = $category['title'];

            $categoriesByGender[$gender['title']][] = $data;
        }
    }

    $entityType = 'category';

    $productQueryParameters = [];

    $whereClauses = [];

    if (isset($_GET['ids'])) {
        $ids = $_GET['ids'];

        $productQueryParameters['ids'] = $ids;

        $whereClauses[] = 'FIND_IN_SET(c.parent_id, :ids)';

    }

    $where = 'and ' . implode(' and ', $whereClauses);

    if (!$whereClauses) {
        $where = '';
    }

    $totalCategories = Category::countCategoriesFiltered($conn, $productQueryParameters, $where);

    $paginator = new Paginator($page, 10, $totalCategories);

    $pageOfCategories = Category::getPageOfCategoriesFiltered($conn, $productQueryParameters, $where, $paginator->limit, $paginator->offset);

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

?>

<main>
    <div class="container">
        <?php if ($error): ?>
            <div><?= $error ?></div>
        <?php else: ?>
            <a href="/admin/add-category.php" class="add-entity">Add category</a>
            <h1 class="entity-list-title">Categories</h1>
            <div class="catalog-filters">
                <form>
                    <?php
                    foreach ($categoriesByGender as $gender => $category) {
                        renderSelectPicker($category, 'ids', $gender . ' categories', '');
                    }

                    ?>
                    <button type="submit" class="catalog-filters-submit">Filter</button>
                </form>
            </div>
            <?php if ($pageOfCategories): ?>
                <div class="entity-list entity-list--category">
                    <div class="entity-list-header">
                        <div class="row">
                            <div class="col">Title</div>
                            <div class="col">Parent Category</div>
                            <div class="col-1"></div>
                        </div>
                    </div>
                    <div class="entity-list-content">
                        <?php foreach ($pageOfCategories as $category): ?>
                            <div class="entity-list-item__wrapper">
                                <div class="entity-list-item">
                                    <div class="row entity-list-item__row">
                                        <div class="col">
                                            <a href="/admin/edit-category.php?id=<?= $category->id ?>">
                                                <?= $category->title ?>
                                            </a>
                                        </div>
                                        <div class="col"><?= $category->rootCategory ?> <?= $category->parentTitle ?></div>
                                        <div class="col-1 entity-list-item-icons">
                                            <div class="entity-list-item-icons__inner">
                                                <a href="/admin/edit-category.php?id=<?= $category->id ?>">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <button type="button" data-toggle="modal"
                                                        data-target="#deleteEntity"
                                                        onclick="passEntityId(<?= $category->id ?>)">
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
        if ($pageOfCategories) {
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
