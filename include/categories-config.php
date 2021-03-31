<?php

$womenRootCategoryId = Category::getRootWomenCategoryId($conn);

$menRootCategoryId = Category::getRootMenCategoryId($conn);

$womenCategories = Category::getCategories($conn, $womenRootCategoryId);

$menCategories = Category::getCategories($conn, $menRootCategoryId);

$womenSubCategories = Category::getSubCategories($conn, $womenCategories);

$menSubCategories = Category::getSubCategories($conn, $menCategories);

return $config = [
    ['flag' => 'women',
        'categories' => $womenSubCategories],
    ['flag' => 'men',
        'categories' => $menSubCategories]
];
