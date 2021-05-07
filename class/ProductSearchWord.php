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

    public static function testF($conn, $wordIds)
    {
        if (!$wordIds) {
            return null;
        }

        if (count($wordIds) === 1) {
            foreach ($wordIds as $wordId) {
                $sql = "select p.*
                        from product_searchwords t0
                        join product p on p.id = t0.product_id
                        where t0.word_id = :firstWordId";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':firstWordId', $wordId, PDO::PARAM_INT);

                $statement->execute();

                return $statement->fetchAll(PDO::FETCH_CLASS, Product::class);
            }

        } else {
            $firstId = 0;

            $join = '';

            $bindData = [];

            foreach ($wordIds as $wordId) {
                $firstId = $wordId;

                break;
            }

            foreach ($wordIds as $i => $wordId) {
                if ($i === 0) {
                    $join = '';

                } else {
                    $join .= " join product_searchwords t$i on t0.product_id = t$i.product_id and t$i.word_id = :wordId$i";

                    $data = [];

                    $data['wordId'] = $wordId;

                    $data['index'] = $i;

                    $bindData[] = $data;
                }
            }

            $sql = "select p.*
                    from product_searchwords t0
                    join product p on p.id = t0.product_id
                    $join
                    where t0.word_id = :firstWordId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':firstWordId', $firstId, PDO::PARAM_INT);

            foreach ($bindData as $data) {
                $index = $data['index'];

                $statement->bindValue(":wordId$index", $data['wordId'], PDO::PARAM_INT);
            }

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS, Product::class);
        }
    }



}