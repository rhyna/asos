<?php

require_once __DIR__ . "/../include/init.php";

$conn = require_once __DIR__ . "/../include/db.php";

try {
    Auth::ifNotLoggedIn();

    global $ROOT;

    $id = $_POST['id'] ?? null;

    if (!$id) {
        throw new BadRequestException('The id is not provided');
    }

    $id = (int)$id;

    $banner = Banner::getBanner($conn, $id);

    if (!$banner) {
        throw new NotFoundException('Such a banner does not exist');
    }

    try {
        $banner->deleteBanner($conn, $id);

    } catch (PDOException $e) {
        throw new Exception('The banner has not been deleted');
    }

    try {
        unlink($ROOT . $banner->image);

    } catch (Throwable $e) {
        throw new Exception('The banner image has not been deleted');
    }

    header("HTTP/2.0 200 OK");

    echo 'Successfully deleted';

} catch (BadRequestException $e) {
    header('HTTP/2.0 400 Bad Request');

    die($e->getMessage());

} catch (NotFoundException $e) {
    header('HTTP/2.0 404 Not Found');

    die($e->getMessage());

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}