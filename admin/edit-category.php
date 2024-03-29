<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

$error = null;

try {
    Auth::ifNotLoggedIn();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new Exception('The id is not provided');
    }

    $id = (int)$id;

    $category = Category::getCategory($conn, $id);

    if (!$category) {
        throw new Exception('Such a category does not exist');
    }

    if ($id === Category::getRootWomenCategoryId($conn) || $id === Category::getRootMenCategoryId($conn)) {
        throw new Exception('Editing root categories is prohibited');
    }

    $categories = Category::getCategoryLevels($conn);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        CategoryController::onPostCategoryAction($category, $conn, 'update');
    }

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

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