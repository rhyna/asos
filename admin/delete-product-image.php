<?php

require_once __DIR__ . '/include/header.php';

if (!isset($_POST['id'])) {
    echo 'The id parameter isn\'t provided or empty';
    exit;
}

if ($_POST['id'] !== (string) ((int) $_POST['id'])) {
    echo 'The id parameter is not a whole number';
    exit;
}

$id = (int) $_POST['id'];

$product = Product::getProduct($conn, $id);

if (!$product) {
    echo 'The product is not found';
    exit;
}

$image = $_POST['image'] ?? null;

$product->deleteProductImage($conn, $image);




