<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    Auth::ifNotLoggedIn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $productId = $_POST['productId'];

        $productMode = $_POST['productMode'];

        $brand = new Brand();

        $brand->fillBrandObject($_POST);

        $brand->createBrand($conn);

        $newBrand = $brand::getBrand($conn, $brand->id);

        echo(json_encode($newBrand));
    }

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());

}





