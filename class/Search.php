<?php

require_once __DIR__ . '/../vendor/markfullmer/porter2/src/Porter2.php';

use markfullmer\porter2\Porter2;

class Search
{

    /**
     * @param string $string
     * @return array
     */
    public static function normalizeString(string $string): array
    {
        $string = str_replace('<', ' <', $string); // adding space before each tag

        $string = strip_tags($string); // removing php and html tags

        $string = strtolower($string); // converting to lowercase

        $string = str_replace("&nbsp;", '', $string);

        $string = str_replace("‘", '', $string);

        $string = str_replace("’", '', $string);

        $string = html_entity_decode($string); // decoding html entities (&nbsp; etc.)

        $array = str_word_count($string, 1, "0123456789"); // splitting string into array removing special characters

        $result = [];

        foreach ($array as $item) {
            $result[] = Porter2::stem($item); // getting basic form of each word
        }

        return $result;

    }

    /**
     * @param array $rawWords
     * @return array
     */
    public static function prepareSearchWordsByProduct(array $rawWords): array
    {
        $normalized = [];

        foreach ($rawWords as $item) {
            $data = [];

            $data['id'] = array($item['id']);

            $data['title'] = self::normalizeString((string)$item['title']);

            $data['productDetails'] = self::normalizeString((string)$item['productDetails']);

            $data['categoryTitle'] = self::normalizeString((string)$item['categoryTitle']);

            $data['parentTitle'] = self::normalizeString((string)$item['parentTitle']);

            $data['rootTitle'] = self::normalizeString((string)$item['rootTitle']);

            $data['brandTitle'] = self::normalizeString((string)$item['brandTitle']);

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

    private function flatWordFields()
    {

    }

    /**
     * @param PDO $conn
     * @param array $wordsByProduct
     * @throws SystemErrorException
     */
    public static function createSearchWordsForProduct(PDO $conn, array $wordsByProduct): void
    {
        foreach ($wordsByProduct as $productId => $item) {
            foreach ($item as $string) {
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
}