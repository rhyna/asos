<?php

require_once __DIR__ . '/../include/init.php';

Auth::ifNotLoggedIn();

$conn = require_once __DIR__ . '/../include/db.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    header('HTTP/2.0 400 Bad Request');

    die('The category id is not provided');
}

if ($id !== (string)((int)$id)) {
    header('HTTP/2.0 422 Validation Error');

    die('The category id is not a valid number');
}

$id = (int)$id;

$category = Category::getCategory($conn, $id);

if (!$category) {
    header('HTTP/2.0 404 Not Found');

    die('The category to delete is not found');
}

if ($category->deleteCategory($conn, $id)) {
    header("HTTP/2.0 200 OK");

    echo 'Successfully deleted';

    if($category->image) {
        unlink($ROOT . $category->image);
    }
} elseif ($category->validationErrors) {
    header('HTTP/2.0 422 Validation Error');

    foreach ($category->validationErrors as $error) {
        echo $error;
    }
} else {
    header('HTTP/2.0 500 Internal Server Error');

    die('A problem occurred. The category has not been deleted');
}







