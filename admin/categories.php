<?php

require_once __DIR__ . '/include/header.php';

$error = null;

$categories = [];

try {
    Auth::ifNotLoggedIn();

    $categories = Category::getCategoryLevels($conn);

    $entityType = 'category';

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<main>
    <div class="container">
        <?php if ($error): ?>
            <div><?= $error ?></div>
        <?php else: ?>
            <a href="/admin/add-category.php" class="add-entity">Add category</a>
            <h1 class="entity-list-title">Categories</h1>
            <div class="entity-list entity-list--category">
                <div class="entity-list-header">
                    <div class="row">
                        <div class="col">Title</div>
                        <div class="col">Parent Category</div>
                        <div class="col-1"></div>
                    </div>
                </div>
                <div class="entity-list-content">
                    <?php foreach ($categories as $rootCategory): ?>
                        <?php foreach ($rootCategory['child_category1'] as $firstLevelCategory): ?>
                            <div class="entity-list-item__wrapper">
                                <div class="entity-list-item">
                                    <div class="row entity-list-item__row">
                                        <div class="col">
                                            <a href="/admin/edit-category.php?id=<?= $firstLevelCategory['id'] ?>">
                                                <?= $firstLevelCategory['title'] ?>
                                            </a>
                                        </div>
                                        <div class="col"><?= $firstLevelCategory['parent_title'] ?></div>
                                        <div class="col-1 entity-list-item-icons">
                                            <div class="entity-list-item-icons__inner">
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
                                </div>
                            </div>

                        <?php endforeach; ?>
                        <?php foreach ($rootCategory['child_category1'] as $firstLevelCategory): ?>
                            <?php foreach ($firstLevelCategory['child_category2'] as $secondLevelCategory): ?>
                                <div class="entity-list-item__wrapper">
                                    <div class="entity-list-item">
                                        <div class="row entity-list-item__row">
                                            <div class="col">
                                                <a href="/admin/edit-category.php?id=<?= $secondLevelCategory['id'] ?>">
                                                    <?= $secondLevelCategory['title'] ?>
                                                </a>
                                            </div>
                                            <div class="col"><?= $firstLevelCategory['parent_title'] ?> <?= $secondLevelCategory['parent_title'] ?></div>
                                            <div class="col-1 entity-list-item-icons">
                                                <div class="entity-list-item-icons__inner">
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
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
if (!$error) {
    require_once __DIR__ . '/include/delete-entity-confirmation.php';

    require_once __DIR__ . '/include/on-entity-deletion-modal.php';
}
?>

<?php require_once __DIR__ . '/include/footer.php'; ?>
