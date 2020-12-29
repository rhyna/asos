<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    Auth::ifNotLoggedIn();

    if (!isset($_POST['id'])) {
        throw new BadRequestException('The id parameter isn\'t provided or empty');
    }

    if ($_POST['id'] !== (string)((int)$_POST['id'])) {
        throw new ValidationErrorException('The id parameter is not a whole number');
    }

    $id = (int)$_POST['id'];

    $product = Product::getProduct($conn, $id);

    if (!$product) {
        throw new NotFoundException('The product is not found');
    }

    $image = $_POST['image'] ?? null;

    if (!$image) {
        throw new BadRequestException('The image is not provided');
    }

    $images = $product->getImagesArray();

    $eligibleImageNames = [];

    foreach ($images as $imageArray) {
        foreach ($imageArray as $key => $value) {
            $eligibleImageNames[] = $key;
        }
    }

    if (!in_array($image, $eligibleImageNames)) {
        throw new NotFoundException('The image to delete is not found in the database');
    }

    if (!$product->deleteProductImage($conn, $image)) {
        throw new Exception('A problem occurred, the image has not been deleted');
    }

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die ($e->getMessage());

} catch (ValidationErrorException $e) {
    header('HTTP/2.0 422 Validation Error');

    die ($e->getMessage());

} catch (NotFoundException $e) {
    header('HTTP/2.0 404 Not Found');

    die ($e->getMessage());

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die ($e->getMessage());
}




