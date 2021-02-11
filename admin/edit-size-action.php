<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    $sizeTitle = $_POST['sizeTitle'] ?? null;

    if (!$sizeTitle) {
        throw new BadRequestException('No size title provided');
    }

    $sizeId = $_POST['sizeId'] ?? null;

    if (!$sizeId) {
        throw new BadRequestException('No size id provided');
    }

    $sizeId = (int)$sizeId;

    if (!isset($_POST['sortOrder'])) {
        throw new BadRequestException('No sorting number field provided in the request');
    }

    $sortOrder = $_POST['sortOrder'] ?: null;

    $sortOrder = (int)$sortOrder;

    if (Size::checkIfSortOrderExists($conn, $sortOrder) && Size::checkIfSortOrderExists($conn, $sortOrder) !== $sizeId) {
        $editSizeError = [
            'errorMessage' => 'sort order exists',
        ];

        die(json_encode($editSizeError));
    }

    $lowerCaseTitle = mb_strtolower($sizeTitle);

    $normalizedTitle = str_replace(' ', '', $lowerCaseTitle);

    $size = Size::getSizeByNormalizedTitle($conn, $normalizedTitle);

    if ($size && (int)$size->id !== $sizeId) {
        $editSizeError = [
            'errorMessage' => 'size exists',
        ];

        die(json_encode($editSizeError));
    }

//    if (!$size) {
        Size::editSize($conn, $sizeId, $sizeTitle, $normalizedTitle, $sortOrder);

        $size = Size::getSize($conn, $sizeId);
//    }

    echo json_encode($size);

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die($e->getMessage());

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}


