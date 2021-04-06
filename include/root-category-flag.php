<?php

$rootCategoryFlag = '';

if (isset($_GET['gender']) && $_GET['gender'] === 'men') {
    $rootCategoryFlag = 'men';

} else if (isset($_GET['gender']) && $_GET['gender'] === 'women') {
    $rootCategoryFlag = 'women';
}

return $rootCategoryFlag;
