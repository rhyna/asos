<?php

require_once __DIR__ . '/include/header.php';

$error = null;

try {
    Auth::ifNotLoggedIn();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new Exception('The id is not provided');
    }

    $id = (int)$id;

    $brand = Brand::getBrand($conn, $id);

    if (!$brand) {
        throw new Exception('Such a brand does not exist');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $brand->fillBrandObject($_POST);

        $brand->updateBrand($conn);

        Url::redirect('/admin/brands.php');
    }

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<main>
    <div class="container">
        <div class="edit-brand-page">
            <div class="admin-title">
                Edit brand
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
