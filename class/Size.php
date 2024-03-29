<?php


class Size
{
    public $id;
    public $title;
    public $normalizedTitle;
    public $sortOrder;

    /**
     * @param PDO $conn
     * @param int $categoryId
     * @return array
     * @throws SystemErrorException
     */
    public static function getSizesByCategory(PDO $conn, int $categoryId): array
    {
        try {
            $sql = "select s.id, s.title, s.sort_order as sortOrder, cs.id 
                    as categorySizeId, cs.category_id as categoryId
                    from size s 
                    join category_size cs 
                    on s.id = cs.size_id 
                    where category_id = :category_id
                    order by sort_order asc";

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
     * @param array $categoryIds
     * @return array
     * @throws SystemErrorException
     */
    public static function getSizesByCategoryArray(PDO $conn, array $categoryIds): array
    {
        try {
            $sql = "select s.id, s.title
                    from size s
                    join category_size cs
                    on s.id = cs.size_id
                    where FIND_IN_SET(cs.category_id, :categoryIds)
                    group by s.id
                    order by min(sort_order) asc";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':categoryIds', implode(',', $categoryIds), PDO::PARAM_STR);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

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
            $sql = "insert into size 
                    (title, normalized_title, sort_order) 
                    values (:title, :normalizedTitle, :sortOrder)";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':title', $this->title, PDO::PARAM_STR);

            $statement->bindValue(':normalizedTitle', $this->normalizedTitle, PDO::PARAM_STR);

            if ($this->sortOrder) {
                $statement->bindValue(':sortOrder', $this->sortOrder, PDO::PARAM_INT);
            } else {
                $statement->bindValue(':sortOrder', $this->sortOrder, PDO::PARAM_NULL);
            }

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
            $sql = "insert into category_size (category_id, size_id) values (:categoryId, :sizeId)";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

            $statement->bindValue(':sizeId', $this->id, PDO::PARAM_INT);

            $statement->execute();

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
            $sql = "select id, 
                    title, 
                    normalized_title as normalizedTitle,
                    sort_order as sortOrder
                    from size 
                    where normalized_title = :normalizedTitle";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':normalizedTitle', $normalizedTitle, PDO::PARAM_STR);

            $statement->execute();

            return $statement->fetchObject(Size::class) ?: null;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @param string $title
     * @param string $normalizedTitle
     * @param int $sortOrder
     * @return void
     * @throws SystemErrorException
     */
    public static function editSize(PDO $conn, int $id, string $title, string $normalizedTitle, int $sortOrder): void
    {
        try {
            $sql = "update size 
                    set title = :title, 
                    normalized_title = :normalizedTitle,
                    sort_order = :sortOrder
                    where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->bindValue(':title', $title, PDO::PARAM_STR);

            $statement->bindValue(':normalizedTitle', $normalizedTitle, PDO::PARAM_STR);

            if ($sortOrder) {
                $statement->bindValue(':sortOrder', $sortOrder, PDO::PARAM_INT);
            } else {
                $statement->bindValue(':sortOrder', $sortOrder, PDO::PARAM_NULL);
            }

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return Size|null
     * @throws SystemErrorException
     */
    public static function getSize(PDO $conn, int $id): ?Size
    {
        try {
            $sql = "select id, 
                    title, 
                    normalized_title as normalizedTitle,
                    sort_order as sortOrder
                    from size
                    where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchObject(Size::class) ?: null;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $categoryId
     * @throws SystemErrorException
     */
    public function deleteSizeFromCategory(PDO $conn, int $categoryId): void
    {
        try {
            $sql = "delete
                    from category_size
                    where size_id = :sizeId and category_id = :categoryId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':sizeId', $this->id, PDO::PARAM_INT);

            $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @return array
     * @throws SystemErrorException
     */
    public function checkSizeProducts(PDO $conn): array
    {
        try {
            $sql = "select id, product_id as productId from product_size where size_id = :sizeId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':sizeId', $this->id, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $sortOrder
     * @return int
     * @throws SystemErrorException
     */
    public static function checkIfSortOrderExists(PDO $conn, int $sortOrder): int
    {
        try {
            $sql = "select id from size where sort_order = :sortOrder";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':sortOrder', $sortOrder, PDO::PARAM_INT);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }
}