<?php

require_once __DIR__ . "/../include/init.php";

Auth::ifNotLoggedIn();

$conn = require_once __DIR__ . "/../include/db.php";

$id = $_POST['id'] ?? null;

if (!$id) {
    header('HTTP/2.0 400 Bad Request');

    die('The product id is not provided');
}

if ($id !== (string)((int)$id)) {
    header('HTTP/2.0 422 Validation Error');

    die('The product id is not a valid number');
}

$id = (int)$id;

$product = Product::getProduct($conn, $id);

if (!$product) {
    header('HTTP/2.0 404 Not Found');

    die('The product to delete is not found');
}

global $ROOT;

if ($product->deleteProduct($conn)) {
    header("HTTP/2.0 200 OK");

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

//    Url::redirect("/admin/products.php");

    echo 'Successfully deleted';


} else {
    header('HTTP/2.0 500 Internal Server Error');

    die('A problem occurred, the product has not been deleted');
}




