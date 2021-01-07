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
     * @throws SystemErrorException
     */
    static public function getAllBrands(PDO $conn): array
    {
        try {
            $sql = "select title, 
                    id, 
                    description_women as descriptionWomen, 
                    description_men as descriptionMen
                    from brand 
                    order by title asc";

            $result = $conn->query($sql);

            return $result->fetchAll(PDO::FETCH_CLASS, Brand::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return Brand|null
     * @throws SystemErrorException
     */
    static public function getBrand(PDO $conn, int $id): ?Brand
    {
        try {
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

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param array $data
     */
    public function fillBrandObject(array $data): void
    {
        $this->title = $data['title'];

        $this->descriptionWomen = $data['descriptionWomen'];

        $this->descriptionMen = $data['descriptionMen'];
    }

    /**
     * @param PDOStatement $statement
     */
    private function fillBrandStatement(PDOStatement $statement): void
    {
        $statement->bindValue(':title', $this->title, PDO::PARAM_STR);

        if ($this->descriptionWomen) {
            $statement->bindValue(':descriptionWomen', $this->descriptionWomen, PDO::PARAM_STR);
        } else {
            $statement->bindValue(':descriptionWomen', $this->descriptionWomen, PDO::PARAM_NULL);
        }

        if ($this->descriptionMen) {
            $statement->bindValue(':descriptionMen', $this->descriptionMen, PDO::PARAM_STR);
        } else {
            $statement->bindValue(':descriptionMen', $this->descriptionMen, PDO::PARAM_NULL);
        }
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function updateBrand(PDO $conn): void
    {
        try {
            $sql = "update brand 
                    set title = :title,
                    description_women = :descriptionWomen,
                    description_men = :descriptionMen
                    where id = :id";

            $statement = $conn->prepare($sql);

            $this->fillBrandStatement($statement);

            $statement->bindValue(':id', $this->id, PDO::PARAM_STR);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function createBrand(PDO $conn): void
    {
        try {
            $sql = "insert into brand 
                    (title, description_women, description_men)
                    values  (:title, :descriptionWomen, :descriptionMen)";

            $statement = $conn->prepare($sql);

            $this->fillBrandStatement($statement);

            $statement->execute();

            $this->id = $conn->lastInsertId();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function deleteBrand(PDO $conn): void
    {
        try {
            $sql = "delete from brand where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $this->id, PDO::PARAM_STR);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @return int
     * @throws SystemErrorException
     */
    public function checkBrandProducts(PDO $conn): int
    {
        try {
            $sql = "select id from product where brand_id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $this->id, PDO::PARAM_STR);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}