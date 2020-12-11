<?php

require_once __DIR__ . "/../include/init.php";

Auth::ifNotLoggedIn();

$conn = require_once __DIR__ . "/../include/db.php";

global $ROOT;

try {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        throw new BadRequestException('The product id is not provided');
    }

    if ($id !== (string)((int)$id)) {
        throw new ValidationErrorException('The product id is not a valid number');

    }

    $id = (int)$id;

    $product = Product::getProduct($conn, $id);

    if (!$product) {
        throw new NotFoundException('The product to delete is not found');

    }

    if ($product->deleteProduct($conn)) {
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

        header("HTTP/2.0 200 OK");

        echo 'Successfully deleted';

    } else {
        throw new Exception('A problem occurred, the product has not been deleted');
    }

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die($e->getMessage());

} catch (ValidationErrorException $e) {
    header('HTTP/2.0 422 Validation Error');

    die($e->getMessage());

} catch (NotFoundException $e) {
    header('HTTP/2.0 404 Not Found');

    die($e->getMessage());

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}




