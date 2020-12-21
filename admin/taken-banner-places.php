<?php

require_once __DIR__ . '/../include/init.php';

$conn = require_once __DIR__ . '/../include/db.php';

try {
    $sql = "select id, banner_place_id from banner where banner_place_id is not null";

    $result = $conn->query($sql);

    $fetchedResult = $result->fetchAll();

    $finalResult = [];

    foreach ($fetchedResult as $item) {
        $array = [
            'bannerId' => $item['id'],
            'bannerPlaceId' => $item['banner_place_id'],
        ];

        $finalResult[] = $array;
    }

    print_r(json_encode($finalResult));

} catch (Throwable $e) {
    header('HTTP/2.0 500 Internal Server Error');

    die($e->getMessage());
}

