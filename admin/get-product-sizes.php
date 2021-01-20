<?php

require_once __DIR__ . '/../include/init.php';

$conn = require_once __DIR__ . '/../include/db.php';

try {
    $categoryId = $_POST['categoryId'] ?? null;

    $categoryId = (int)$categoryId;

    $parentCategoryId = Category::getParentCategory($conn, $categoryId);

    $sizesByCategory = Size::getSizesByCategory($conn, $parentCategoryId);

    echo json_encode($sizesByCategory);

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}


