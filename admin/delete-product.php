<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

$id = $_POST['id'] ?? null;

if (!$id) {
    echo 'The product id is not provided';
    exit;
}

if ($id !== (string)((int)$id)) {
    echo 'The product id is not a valid number';
    exit;
}

$id = (int)$id;

$product = Product::getProduct($conn, $id);

if (!$product) {
    echo 'The product to delete is not found';
    exit;
}

global $ROOT;

if ($product->deleteProduct($conn)) {
    $images = $product->getImagesArray();

    foreach ($images as $imageArray) {
        foreach ($imageArray as $key => $value) {
            if ($product->$key == '') {
                continue;
            }

            $imageToDelete = $product->$key;

            unlink($ROOT . $imageToDelete);
        }
    }

    Url::redirect("/admin/products.php");
} else {
    echo 'A problem occurred, the product has not been deleted';
    exit;
}




