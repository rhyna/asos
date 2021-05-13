<?php

require_once __DIR__ . "/../config.php";

require_once __DIR__ . '/../class/Database.php';

require_once __DIR__ . '/../class/SearchWord.php';

require_once __DIR__ . '/../class/ProductSearchWord.php';

require_once __DIR__ . '/../class/Product.php';

require_once __DIR__ . '/../vendor/markfullmer/porter2/src/Porter2.php';

use markfullmer\porter2\Porter2;

$conn = require_once __DIR__ . '/../include/db.php';

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

function normalizeString($string)
{
    $string = str_replace('<', ' <', $string); // adding space before each tag

    $string = strip_tags($string); // removing php and html tags

    $string = strtolower($string); // converting to lowercase

    $string = str_replace("&nbsp;", '', $string);

    $string = str_replace("‘", '', $string);

    $string = str_replace("’", '', $string);

    $string = html_entity_decode($string); // decoding html entities (&nbsp; etc.)

    return $wordArray = str_word_count($string, 1, "0123456789"); // splitting string into array removing special characters

//    $string = preg_replace('/[^a-z0-9]/', ' ', $string); // removing special characters
//
//    $string = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string); // removing multiple whitespaces, tabs, and newlines
//
//    return trim($string); // trimming whitespaces and other symbols at the start and end of the string

}

$raw = getSearchDataForAllProducts($conn);

$processed = [];

foreach ($raw as $item) {
    $data = [];

    $data['id'] = array($item['id']);

    $data['title'] = normalizeString($item['title']);

    $data['productDetails'] = normalizeString($item['productDetails']);

    $data['categoryTitle'] = normalizeString($item['categoryTitle']);

    $data['parentTitle'] = normalizeString($item['parentTitle']);

    $data['rootTitle'] = normalizeString($item['rootTitle']);

    $data['brandTitle'] = normalizeString($item['brandTitle']);

    $processed[] = $data;
}

$wordsByProduct = [];

foreach ($processed as $item) {
    $id = $item['id'][0];

    unset($item['id']);

    $data = [];

    foreach ($item as $key => $array) {
        foreach ($array as $string) {
            $data[] = $string;
        }
    }

    $wordsByProduct[$id] = $data;
}

foreach ($wordsByProduct as $productId => $item) {
    foreach ($item as $string) {
        $string = Porter2::stem($string);

        $word = new SearchWord();

        $word->word = $string;

        //$word->createSearchWord($conn);

        $productSearchWord = new ProductSearchWord();

        $productSearchWord->productId = $productId;

        $productSearchWord->wordId = $word->id;

        //$productSearchWord->createProductSearchWord($conn);
    }
}

/***/

$test = "women dress";
//$test = "men dress";
//$test = "dbbdb dress";
//$test = "dbbdb";
//$test = "dress dbbdb women";


// удалять дубли? - в итоге будет два раза dress

$test = normalizeString($test);

$array = [];

foreach ($test as $item) {
    $item = Porter2::stem($item);

    $array[] = $item;
}

$wordIds = SearchWord::getSearchWords($conn, $array);

//$wordIds = [];
//
//foreach ($searchWordsData as $searchWord) {
//    $wordIds[] = $searchWord;
//}

$searchResult = Product::getProductsBySearchWords($conn, $wordIds);

if (!$searchResult) {
    echo 'No products matching the search query have been found';
} else {
    echo '<pre>';
    var_dump($searchResult);
}

// переделала метод getSearchWords, на каждое слово в цикле - отдельный sql запрос,
// если id не найден, возвращается 0
// в прошлом варианте записи метода, если id не найден, он не добавлялся в массив,
// и поиск шел по остальным найденным словам
// мб есть варианты лучше
// нормальная ли логика работы со словами при edit и add

















