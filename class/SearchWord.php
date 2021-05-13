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
     * @param array $words
     * @return array
     * @throws SystemErrorException
     */
    public static function getSearchWords(PDO $conn, array $words): array
    {
        try {
            $result = [];

            foreach ($words as $word) {
                $sql = "select id from search_words where word = :word";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':word', $word, PDO::PARAM_STR);

                $statement->execute();

                $fetchedResult = (int)$statement->fetchColumn();

                $result[] = $fetchedResult;
            }

//        $sql = "select id from search_words where FIND_IN_SET(word, :words)";
//
//        $statement = $conn->prepare($sql);
//
//        $statement->bindValue(':words', implode(',', $words), PDO::PARAM_STR);
//
//        $statement->execute();
//
//        return $statement->fetchAll(PDO::FETCH_CLASS, SearchWord::class);

            return $result;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}