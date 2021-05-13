<?php

require_once __DIR__ . '/../vendor/markfullmer/porter2/src/Porter2.php';

use markfullmer\porter2\Porter2;

/**
 * @param $string
 * @return string[]
 */
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
}

/**
 * @param array $rawWords
 * @return array
 */
function prepareSearchWordsByProduct(array $rawWords): array
{
    $normalized = [];

    foreach ($rawWords as $item) {
        $data = [];

        $data['id'] = array($item['id']);

        $data['title'] = normalizeString($item['title']);

        $data['productDetails'] = normalizeString($item['productDetails']);

        $data['categoryTitle'] = normalizeString($item['categoryTitle']);

        $data['parentTitle'] = normalizeString($item['parentTitle']);

        $data['rootTitle'] = normalizeString($item['rootTitle']);

        $data['brandTitle'] = normalizeString($item['brandTitle']);

        $normalized[] = $data;
    }

    $wordsByProduct = [];

    foreach ($normalized as $item) {
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

    return $wordsByProduct;
}

/**
 * @param PDO $conn
 * @param array $wordsByProduct
 * @throws SystemErrorException
 */
function createSearchWordsForProduct(PDO $conn, array $wordsByProduct): void
{
    foreach ($wordsByProduct as $productId => $item) {
        foreach ($item as $string) {
            $string = Porter2::stem($string);

            $word = new SearchWord();

            $word->word = $string;

            $word->createSearchWord($conn);

            $productSearchWord = new ProductSearchWord();

            $productSearchWord->productId = $productId;

            $productSearchWord->wordId = $word->id;

            $productSearchWord->createProductSearchWord($conn);
        }
    }
}
