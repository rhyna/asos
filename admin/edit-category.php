<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

if (isset($_GET['id']) && $_GET['id'] === (string)(int)$_GET['id']) {
    $category = Category::getCategory($conn, (int)$_GET['id']);
} else {
    $category = null;
}

if ((int)$_GET['id'] === Category::getRootWomenCategoryId($conn) || (int)$_GET['id'] === Category::getRootMenCategoryId($conn)) {
    echo 'Editing root categories is prohibited';
    exit;
}

$categories = Category::getCategoryLevels($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category->fillCategoryObject($_POST);

    $category->validateCategory();

    if ($_FILES['image']['name']) {
        $category->validateCategoryImage($_FILES['image']);
    }

    if (!$category->validationErrors && !$category->imageValidationErrors) {
        if ($category->updateCategory($conn)) {
            if ($_FILES['image']['name']) {
                $category->updateCategoryImage($conn, $_FILES['image']);
            }
        }
    }
}

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

<?php require_once __DIR__ . '/include/footer.php'; ?>