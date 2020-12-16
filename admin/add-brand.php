<?php

require_once __DIR__ . '/include/header.php';

$error = null;

try {
    Auth::ifNotLoggedIn();

    $brand = new Brand();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $brand->fillBrandObject($_POST);

        if ($brand->createBrand($conn)) {
            Url::redirect('/admin/edit-brand.php?id=' . $brand->id);
        }
    }


} catch (Throwable $e) {
    $error = $e->getMessage();
}

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
