<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

$error = null;

try {
    Auth::ifNotLoggedIn();

    $brand = new Brand();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $brand->fillBrandObject($_POST);

        $brand->createBrand($conn);

        Url::redirect('/admin/edit-brand.php?id=' . $brand->id);

    }

} catch (Throwable $e) {
    $error = $e->getMessage();
}

require_once __DIR__ . '/include/header.php';

?>

<main>
    <div class="container">
        <div class="edit-brand-page">
            <div class="admin-title">
                Add brand
            </div>
            <?php
            if ($error) {
                echo $error;
            } else {
                include_once __DIR__ . '/include/brand-form.php';
            }
            ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
