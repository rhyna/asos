<?php


class SearchWord
{
    public $id;
    public $word;

    /**
     * @param PDO $conn
     * @return int
     * @throws SystemErrorException
     */
    public function getWordIdByName(PDO $conn): int
    {
        try {
            $sql = "select id from search_words where word = :word";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':word', $this->word, PDO::PARAM_STR);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }

    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function createSearchWord(PDO $conn): void
    {
        try {
            $id = self::getWordIdByName($conn);

            if (!$id) {
                $sql = "insert into search_words (word) values (:word)";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':word', $this->word, PDO::PARAM_STR);

                $statement->execute();

                $this->id = $conn->lastInsertId();

            } else {
                $this->id = $id;
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }


    }

    /**
     * @param PDO $conn
     * @param int $productId
     * @throws SystemErrorException
     */
    public function createProductWord(PDO $conn, int $productId): void
    {
        try {
            if (!self::wordInProductExists($conn, $productId)) {
                $sql = "insert into product_searchwords 
                (product_id, word_id)
                values (:productId, :wordId)";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':productId', $productId, PDO::PARAM_INT);

                $statement->bindValue(':wordId', $this->id, PDO::PARAM_INT);

                $statement->execute();
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $productId
     * @return int
     * @throws SystemErrorException
     */
    public function wordInProductExists(PDO $conn, int $productId): int
    {
        try {
            $sql = "select count(*) from product_searchwords 
                where product_id = :productId
                and word_id = :wordId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':productId', $productId, PDO::PARAM_INT);

            $statement->bindValue(':wordId', $this->id, PDO::PARAM_INT);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}