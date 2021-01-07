<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    Auth::ifNotLoggedIn();

    $id = $_POST['id'] ?? null;

    if (!$id) {
        throw new BadRequestException('Id is not provided');
    }

    $id = (int)$id;

    $brand = Brand::getBrand($conn, $id);

    if (!$brand) {
        throw new NotFoundException('Such a brand does not exist');
    }

    $hasProducts = $brand->checkBrandProducts($conn);

    if ($hasProducts) {
        throw new ValidationErrorException("Cannot delete a brand that has products linked to it. <br> Delete products first");
    }

    $brand->deleteBrand($conn);

    header('HTTP/2.0 200 OK');

    die('Successfully deleted');

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




