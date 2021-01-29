<?php

require_once __DIR__ . '/../include/init.php';

$conn = require_once __DIR__ . '/../include/db.php';

try {
    $categoryId = $_POST['categoryId'] ?? null;

    if (!$categoryId) {
        throw new BadRequestException('No category provided');
    }

    $categoryId = (int)$categoryId;

    $sizesByCategory = Size::getSizesByCategory($conn, $categoryId);

    echo json_encode($sizesByCategory);

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die($e->getMessage());
}

catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}
