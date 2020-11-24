<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

if (isset($_GET['id']) && $_GET['id'] === (string)(int)$_GET['id']) {
    $category = Category::getCategory($conn, (int)$_GET['id']);
} else {
    $category = null;
}

$categories = Category::getCategoryLevels($conn);

$classMode = 'edit';

?>

<main>
    <div class="container">
        <div class="edit-category-page">
            <div class="admin-title">
                Edit category
            </div>
            <?php
            if (!$category) {
                echo 'Such a category doesn\'t exist';
            } else {
                include_once __DIR__ . '/include/category-form.php';
            }
            ?>
        </div>
    </div>
</main>

