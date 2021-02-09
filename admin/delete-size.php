<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    $sizeId = $_POST['id'] ?? null;

    if (!$sizeId) {
        throw new BadRequestException('The size id is not provided');
    }

    $sizeId = (int)$sizeId;

    $categoryId = $_POST['categoryId'] ?? null;

    if (!$categoryId) {
        throw new BadRequestException('The category id is not provided');
    }

    $categoryId = (int)$categoryId;

    $category = Category::getCategory($conn, $categoryId);

    if (!$category) {
        throw new NotFoundException('Such a category does not exist');
    }

    $size = Size::getSize($conn, $sizeId);

    if (!$size) {
        throw new NotFoundException('Such a size does not exist');
    }

    $sizeProducts = $size->checkSizeProducts($conn);

    $sizeProductsIds = [];

    foreach ($sizeProducts as $sizeProduct) {
        $sizeProductsIds[] = $sizeProduct['productId'];
    }

    if ($sizeProducts) {
        $productsExistInCategory = Product::checkProductsInParentCategory($conn, $sizeProductsIds, $categoryId);

        if ($productsExistInCategory) {
            throw new ValidationErrorException('In the current category there are product(s) of this size. </br> Delete the size from the product(s) first');
        }
    }

    $size->deleteSizeFromCategory($conn, $categoryId);

    echo 'Successfully deleted';

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die($e->getMessage());

} catch (NotFoundException $e) {
    header('HTTP/2.0 404 Not Found');

    die($e->getMessage());

} catch (ValidationErrorException $e) {
    header('HTTP/2.0 422 Validation Error');

    die($e->getMessage());

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}




