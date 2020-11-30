<?php

require_once __DIR__ . "/../include/init.php";

Auth::ifNotLoggedIn();

$conn = require_once __DIR__ . "/../include/db.php";

$id = $_POST['id'] ?? null;

if (!$id) {
    header('HTTP/2.0 404 Not Found');
    die ('The category id is not provided');
}

$id = (int)$id;

$image = $_POST['image'] ?? null;

global $ROOT;

if (!$image) {
    header('HTTP/2.0 400 Bad Request');
    die ('The image is not provided');
}

$category = Category::getCategory($conn, $id);

if (!$category) {
    header('HTTP/2.0 404 Not Found');
    die ('The category is not found');
}

if ($category::deleteCategoryImage($conn, $id)) {
    unlink($ROOT . $image);
} else {
    header('HTTP/2.0 500 Internal Server Error');
    die ('A problem occurred, the image has not been deleted');
}










