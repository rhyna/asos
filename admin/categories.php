<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

$categories = Category::getCategoryLevels($conn);

$entityType = 'category';

?>

<main>
    <div class="container">
        <a href="/admin/add-category.php" class="add-entity">Add category</a>
        <h1 class="admin-list-title">Categories</h1>
        <div class="allcategories-list">
            <div class="allcategories-list-header">
                <div class="row">
                    <div class="col">Title</div>
                    <div class="col">Parent Category</div>
                    <div class="col-1"></div>
                </div>
            </div>
            <div class="allcategories-list-content">
                <?php foreach ($categories as $rootCategory): ?>
                    <?php foreach ($rootCategory['child_category1'] as $firstLevelCategory): ?>
                        <div class="allcategories-list-item">
                            <div class="row">
                                <div class="col">
                                    <a href="/edit-category.php?id=<?= $firstLevelCategory['id'] ?>">
                                        <?= $firstLevelCategory['title'] ?>
                                    </a>
                                </div>
                                <div class="col"><?= $firstLevelCategory['parent_title'] ?></div>
                                <div class="col-1 allcategories-list-item-icons">
                                    <a href="/admin/edit-category.php?id=<?= $firstLevelCategory['id'] ?>">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <button type="button" data-toggle="modal"
                                            data-target="#deleteEntity"
                                            onclick="passEntityId(<?= $firstLevelCategory['id'] ?>)">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($rootCategory['child_category1'] as $firstLevelCategory): ?>
                        <?php foreach ($firstLevelCategory['child_category2'] as $secondLevelCategory): ?>
                            <div class="allcategories-list-item">
                                <div class="row">
                                    <div class="col">
                                        <a href="/edit-category.php?id=<?= $secondLevelCategory['id'] ?>">
                                            <?= $secondLevelCategory['title'] ?>
                                        </a>
                                    </div>
                                    <div class="col"><?= $firstLevelCategory['parent_title'] ?> <?= $secondLevelCategory['parent_title'] ?></div>
                                    <div class="col-1 allcategories-list-item-icons">
                                        <a href="/admin/edit-category.php?id=<?= $secondLevelCategory['id'] ?>">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <button type="button" data-toggle="modal"
                                                data-target="#deleteEntity"
                                                onclick="passEntityId(<?= $secondLevelCategory['id'] ?>)">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/delete-entity-confirmation.php'; ?>

<?php require_once __DIR__ . '/include/on-entity-deletion-modal.php'; ?>

<?php require_once __DIR__ . '/include/footer.php'; ?>
