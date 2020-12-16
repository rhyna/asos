<?php

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

$error = null;

try {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new BadRequestException('The id is not provided');
    }

    $id = (int)$id;

    $category = Category::getCategory($conn, $id);

    if (!$category) {
        throw new NotFoundException('Such a category does not exist');
    }

    if ($id === Category::getRootWomenCategoryId($conn) || $id === Category::getRootMenCategoryId($conn)) {
        throw new ValidationErrorException('Editing root categories is prohibited');
    }

    $categories = Category::getCategoryLevels($conn);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        CategoryController::onPostCategoryAction($category, $conn, 'update');
    }

} catch (BadRequestException $e) {
    $error = $e->getMessage();

} catch (NotFoundException $e) {
    $error = $e->getMessage();

} catch (ValidationErrorException $e) {
    $error = $e->getMessage();

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

    <main>
        <div class="container">
            <div class="edit-category-page">
                <div class="admin-title">
                    Edit category
                </div>
                <?php
                if ($error) {
                    echo $error;
                } else {
                    include_once __DIR__ . '/include/category-form.php';
                }
                ?>
            </div>
        </div>
    </main>

<?php require_once __DIR__ . '/include/footer.php'; ?>