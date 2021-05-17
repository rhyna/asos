<?php

require_once __DIR__ . "/include/init.php";

$conn = require_once __DIR__ . '/include/db.php';

function getSearchDataForAllProducts($conn)
{
    $sql = "select p.id, 
            p.title,
            p.product_details as productDetails,
            c.title as categoryTitle,
            pc.title as parentTitle,
            pc1.title as rootTitle,
            b.title as brandTitle
            from product p
            left join brand b on b.id = p.brand_id
            join category c on c.id = p.category_id
            join category pc on c.parent_id = pc.id
            join category pc1 on pc.parent_id = pc1.id";

    $result = $conn->query($sql);

    return $result->fetchAll(PDO::FETCH_ASSOC);
}

$raw = getSearchDataForAllProducts($conn);

$wordsByProduct = Search::prepareSearchWordsByProduct($raw);


foreach ($wordsByProduct as $productId => $item) {
    foreach ($item as $string) {
        $word = new SearchWord();

        $word->word = $string;

        //$word->createSearchWord($conn);

        $productSearchWord = new ProductSearchWord();

        $productSearchWord->productId = $productId;

        $productSearchWord->wordId = $word->id;

        //$productSearchWord->createProductSearchWord($conn);
    }
}




















