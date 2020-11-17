<?php


class Brand
{
    public $id;
    public $title;
    public $description;

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    static public function getAllBrands(PDO $conn): array
    {
        $sql = "select title, id from brand order by title asc";

        $result = $conn->query($sql);

        $fetchedResult = $result->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($fetchedResult) && $fetchedResult) {
            return $fetchedResult;
        } else {
            throw new Exception('The brand list is empty or a fetch error occurred');
        }
    }
}