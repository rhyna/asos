<?php

/**
 * @var PDO $conn ;
 */

$rootCategoryFlag = '';

if (isset($_GET['gender']) && $_GET['gender'] === 'men') {
    $rootCategoryFlag = 'men';

} else if (isset($_GET['gender']) && $_GET['gender'] === 'women') {
    $rootCategoryFlag = 'women';
}

if (isset($categoryId)) {
    try {
        $parentCategoryId = Category::getParentCategory($conn, $categoryId);

        $rootCategoryId = Category::getParentCategory($conn, $parentCategoryId);

        $rootCategory = Category::getCategory($conn, $rootCategoryId);

    } catch (Throwable $e) {
        throw new SystemErrorException();
    }


    if ((int)$rootCategory->rootWomenCategory === 1) {
        $rootCategoryFlag = 'women';
    }

    if ((int)$rootCategory->rootMenCategory === 1) {
        $rootCategoryFlag = 'men';
    }
}

return $rootCategoryFlag;

