<?php

require_once __DIR__ . '/../include/init.php';

Auth::ifNotLoggedIn();

$conn = require_once __DIR__ . '/../include/db.php';

global $ROOT;

try {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        throw new BadRequestException('The category id is not provided');
    }

    if ($id !== (string)((int)$id)) {
        throw new ValidationErrorException('The category id is not a valid number');
    }

    $id = (int)$id;

    $category = Category::getCategory($conn, $id);

    if (!$category) {
        throw new NotFoundException('The category to delete is not found');
    }

    if ($category->deleteCategory($conn, $id)) {
        if ($category->image) {
            unlink($ROOT . $category->image);
        }

        header("HTTP/2.0 200 OK");

        echo 'Successfully deleted';

    } elseif ($category->validationErrors) {
        $errors = implode('<br>', $category->validationErrors);

        throw new ValidationErrorException($errors);

    } else {
        throw new Exception('A problem occurred, the category has not been deleted');
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






