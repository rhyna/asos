<?php


class Size
{
    public $id;
    public $title;

    /**
     * @param PDO $conn
     * @return array
     * @throws SystemErrorException
     */
    public static function getAllSizes(PDO $conn): array
    {
        try {
            $sql = "select * from size";

            $result = $conn->query($sql);

            return $result->fetchAll(PDO::FETCH_CLASS, Size::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $categoryId
     * @return array
     * @throws SystemErrorException
     */
    public static function getSizesByCategory(PDO $conn, int $categoryId): array
    {
        try {
            $sql = "select s.*, cs.id 
                as categorySizeId, cs.category_id 
                from size s 
                join category_size cs 
                on s.id = cs.size_id 
                where category_id = :category_id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS, Size::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}