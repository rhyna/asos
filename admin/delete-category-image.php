<?php

require_once __DIR__ . "/../include/init.php";

Auth::ifNotLoggedIn();

$conn = require_once __DIR__ . "/../include/db.php";

global $ROOT;

try {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        throw new BadRequestException('The category id is not provided');
    }

    $id = (int)$id;

    $image = $_POST['image'] ?? null;

    if (!$image) {
        throw new BadRequestException('The image is not provided');
    }

    $category = Category::getCategory($conn, $id);

    if (!$category) {
        throw new NotFoundException('The category is not found');
    }

    if ($category::deleteCategoryImage($conn, $id)) {
        unlink($ROOT . $image);

    } else {
        throw new Exception('A problem occurred, the image has not been deleted');
    }

} catch (NotFoundException $e) {
    header('HTTP/2.0 404 Not Found');

    die ($e->getMessage());

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die ($e->getMessage());

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die ($e->getMessage());
}










