<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    Auth::ifNotLoggedIn();

    global $ROOT;

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

    $category::deleteCategoryImage($conn, $id);

    try {
        unlink($ROOT . $image);

    } catch (Throwable $e) {
        throw new Exception('Unable to delete the image file');
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










