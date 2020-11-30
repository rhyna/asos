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
    CategoryController::onPostCategoryAction($category, $conn, 'update');
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