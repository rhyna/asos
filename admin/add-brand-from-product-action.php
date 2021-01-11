<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    Auth::ifNotLoggedIn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['productId'];

        $brand = new Brand();

        $brand->fillBrandObject($_POST);

        $brand->createBrand($conn);

        Url::redirect('/admin/edit-product.php?id=' . $productId);
    }

} catch (Throwable $e) {
    echo $e->getMessage();
}





