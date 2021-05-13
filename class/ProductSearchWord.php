<?php


class ProductSearchWord
{
    public $id;
    public $productId;
    public $wordId;

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function createProductSearchWord(PDO $conn): void
    {
        try {
            if (!self::searchWordInProductExists($conn)) {
                $sql = "insert into product_searchwords 
                (product_id, word_id)
                values (:productId, :wordId)";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':productId', $this->productId, PDO::PARAM_INT);

                $statement->bindValue(':wordId', $this->wordId, PDO::PARAM_INT);

                $statement->execute();

                $this->id = $conn->lastInsertId();
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @return int
     * @throws SystemErrorException
     */
    public function searchWordInProductExists(PDO $conn): int
    {
        try {
            $sql = "select count(*) from product_searchwords 
                    where product_id = :productId
                    and word_id = :wordId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':productId', $this->productId, PDO::PARAM_INT);

            $statement->bindValue(':wordId', $this->wordId, PDO::PARAM_INT);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $productId
     * @throws SystemErrorException
     */
    public static function deleteProductSearchWords(PDO $conn, int $productId)
    {
        try {
            $sql = "delete from product_searchwords 
                    where product_id = :productId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':productId', $productId, PDO::PARAM_INT);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}