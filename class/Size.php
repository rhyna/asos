<?php


class Size
{
    public $id;
    public $title;
    public $normalizedTitle;

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

    /**
     * @param PDO $conn
     * @return void
     * @throws SystemErrorException
     */
    public function addSize(PDO $conn): void
    {
        try {
            $sql = "insert into size (title, normalized_title) values (:title, :normalizedTitle)";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':title', $this->title, PDO::PARAM_STR);

            $statement->bindValue(':normalizedTitle', $this->normalizedTitle, PDO::PARAM_STR);

            $statement->execute();

            $this->id = $conn->lastInsertId();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $categoryId
     * @throws SystemErrorException
     */
    public function addSizeToCategory(PDO $conn, int $categoryId): void
    {
        try {
            if (!$this->checkSizeInCategory($conn, $categoryId)) {
                $sql = "insert into category_size (category_id, size_id) values (:categoryId, :sizeId)";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

                $statement->bindValue(':sizeId', $this->id, PDO::PARAM_INT);

                $statement->execute();
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $categoryId
     * @return int
     * @throws SystemErrorException
     */
    public function checkSizeInCategory(PDO $conn, int $categoryId): int
    {
        try {
            $sql = "select id from category_size where size_id = :sizeId and category_id = :categoryId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

            $statement->bindValue(':sizeId', $this->id, PDO::PARAM_INT);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param string $normalizedTitle
     * @return Size|null
     * @throws SystemErrorException
     */
    public static function getSizeByNormalizedTitle(PDO $conn, string $normalizedTitle): ?Size
    {
        try {
            $sql = "select * from size where normalized_title = :normalizedTitle";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':normalizedTitle', $normalizedTitle, PDO::PARAM_STR);

            $statement->execute();

            return $statement->fetchObject(Size::class) ?: null;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

}