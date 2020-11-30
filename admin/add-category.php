<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

$category = new Category();

$categories = Category::getCategoryLevels($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (CategoryController::onPostCategoryAction($category, $conn, 'create')) {
        $id = $category->id;

        Url::redirect("/admin/edit-category.php?id=$id");
    }
}

?>

<main>
    <div class="container">
        <div class="add-category-page">
            <div class="admin-title">
                Add category
            </div>
            <?php include_once __DIR__ . '/include/category-form.php' ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
