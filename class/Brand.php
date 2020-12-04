<?php


class Brand
{
    public $id;
    public $title;
    public $descriptionWomen;
    public $descriptionMen;

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    static public function getAllBrands(PDO $conn): array
    {
        $sql = "select title, id, description_women as descriptionWomen, description_men as descriptionMen
                from brand order by title asc";

        $result = $conn->query($sql);

        $fetchedResult = $result->fetchAll(PDO::FETCH_CLASS, Brand::class);

        if (is_array($fetchedResult) && $fetchedResult) {
            return $fetchedResult;
        } else {
            throw new Exception('The brand list is empty or a fetch error occurred');
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return Brand|null
     */
    static public function getBrand(PDO $conn, int $id): ?Brand
    {
        $sql = "select title, 
                id, 
                description_women as descriptionWomen, 
                description_men as descriptionMen
        from brand
        where id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetchObject(Brand::class) ?: null;
    }
}