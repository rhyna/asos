<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    Auth::ifNotLoggedIn();

    $sizeTitle = $_POST['size'] ?? null;

    if (!$sizeTitle) {
        throw new BadRequestException('No size title provided');
    }

    $categoryId = $_POST['categoryId'] ?? null;

    if (!$categoryId) {
        throw new BadRequestException('No category id provided');
    }

    $categoryId = (int)$categoryId;

    $lowerCaseTitle = mb_strtolower($sizeTitle);

    $normalizedTitle = str_replace(' ', '', $lowerCaseTitle);

    $size = Size::getSizeByNormalizedTitle($conn, $normalizedTitle);

    if (!$size) {
        $size = new Size();

        $size->title = $sizeTitle;

        $size->normalizedTitle = $normalizedTitle;

        $size->addSize($conn);
    }

    $size->addSizeToCategory($conn, $categoryId);

    echo json_encode($size);

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die($e->getMessage());

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}