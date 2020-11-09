<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

if (!isset($_POST['id'])) {
    header('HTTP/1.1 500 Internal Server Error');
    die ('The id parameter isn\'t provided or empty');
}

if ($_POST['id'] !== (string)((int)$_POST['id'])) {
    header('HTTP/1.1 500 Internal Server Error');
    die ('The id parameter is not a whole number');
}

$id = (int)$_POST['id'];

$product = Product::getProduct($conn, $id);

if (!$product) {
    header('HTTP/1.1 500 Internal Server Error');
    die ('The product is not found');
}

$image = $_POST['image'] ?? null;

if (!$image) {
    header('HTTP/1.1 500 Internal Server Error');
    die ('The image is not provided');
}

$images = $product->getImagesArray();

$eligibleImageNames = [];

foreach ($images as $imageArray) {
    foreach ($imageArray as $key => $value) {
        $eligibleImageNames[] = $key;
    }
}

if (!in_array($image, $eligibleImageNames)) {
    header('HTTP/1.1 500 Internal Server Error');
    die ('The image to delete is not found in the database');
}

$product->deleteProductImage($conn, $image);




