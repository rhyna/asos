<?php


class Category
{
    public static $categoryLevels = [];
    public $validationErrors = [];
    public $imageValidationErrors = [];
    public $id;
    public $parentId;
    public $title;
    public $description;
    public $image;
    public $rootWomenCategory;
    public $rootMenCategory;

    /**
     * @param PDO $conn
     * @param int $id
     * @return Category|null
     * @throws SystemErrorException
     */

    static public function getCategory(PDO $conn, int $id): ?Category
    {
        try {
            $sql = "select id, 
                    parent_id as parentId, 
                    title, 
                    description, 
                    image, 
                    root_women_category as rootWomenCategory, 
                    root_men_category as rootMenCategory
                    from category where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchObject(Category::class) ?: null;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @return int
     */
    static public function getRootWomenCategoryId(PDO $conn): int
    {
        $sql = 'select id from category where root_women_category = 1';

        $result = $conn->query($sql);

        return (int)$result->fetchColumn();

    }

    /**
     * @param PDO $conn
     * @return int
     */
    static public function getRootMenCategoryId(PDO $conn): int
    {
        $sql = 'select id from category where root_men_category = 1';

        $result = $conn->query($sql);

        return (int)$result->fetchColumn();
    }

    /**
     * @param PDO $conn
     * @param int $parentId
     * @return array
     */
    static public function getCategories(PDO $conn, int $parentId): array
    {
        $sql = "select title, id from category where parent_id = $parentId";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @param PDO $conn
     * @param array $categories
     * @return array
     */
    static public function getSubCategories(PDO $conn, array $categories): array
    {
        $subCategories = [];

        foreach ($categories as $category) {
            $sql = "select * from category where parent_id = " . $category['id'];

            $result = $conn->query($sql);

            $subCategories[$category['title']] = $result->fetchAll(PDO::FETCH_ASSOC);
        }

        return $subCategories;
    }

    /**
     * @param array $subCategories
     * @return array
     */
    static public function getPreviewSubCategories(array $subCategories): array
    {
        return $previewSubCategories = array_slice($subCategories, 0, 2);
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    static public function getRootCategories(PDO $conn): void
    {
        try {
            $rootCategoriesSQL = 'select title, id from category where parent_id IS NULL';

            $rootCategories = $conn->query($rootCategoriesSQL);

            self::$categoryLevels = $rootCategories->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    static public function getFirstLevelCategories(PDO $conn): void
    {
        try {
            foreach (self::$categoryLevels as &$categoryLevel) {
                $firstLevelCategoriesSQL =
                    "select category.title, category.id, category.parent_id, c.title as parent_title
                        from category 
                        left join category c on category.parent_id = c.id
                        where category.parent_id = $categoryLevel[id]";

                $firstLevelCategories = $conn->query($firstLevelCategoriesSQL);

                $categoryLevel['child_category1'] = $firstLevelCategories->fetchAll(PDO::FETCH_ASSOC);
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    static public function getSecondLevelCategories(PDO $conn): void
    {
        try {
            foreach (self::$categoryLevels as &$categoryLevel) {

                foreach ($categoryLevel['child_category1'] as &$childCategory) {
                    $secondLevelCategoriesSQL =
                        "select category.title, category.id, category.parent_id, c.title as parent_title
                            from category
                            left join category c on category.parent_id = c.id
                            where category.parent_id = $childCategory[id]";

                    $secondLevelCategories = $conn->query($secondLevelCategoriesSQL);

                    $childCategory['child_category2'] = $secondLevelCategories->fetchAll(PDO::FETCH_ASSOC);
                }
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @return array
     * @throws SystemErrorException
     */
    static public function getCategoryLevels(PDO $conn): array
    {
        Category::getRootCategories($conn);

        Category::getFirstLevelCategories($conn);

        Category::getSecondLevelCategories($conn);

        return self::$categoryLevels;
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     * @throws SystemErrorException
     */
    public function deleteCategory(PDO $conn, int $id): bool
    {
        try {
            if ($this->isNotParent($conn, $id) && $this->ifNoProducts($conn, $id)) {
                $sql = "delete from category where id = :id";

                $statement = $conn->prepare($sql);

                $statement->bindValue(':id', $id, PDO::PARAM_INT);

                return $statement->execute();

            } else {
                return false;
            }

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     * @throws SystemErrorException
     */
    private function isNotParent(PDO $conn, int $id): bool
    {
        try {
            $sql = "select id from category where parent_id = $id";

            $result = $conn->query($sql);

            $fetchedResult = $result->fetchAll();

            if ($fetchedResult) {
                $this->validationErrors[] = 'Impossible to delete a category that has child categories';
            }

            return $this->validationErrors ? false : true;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     * @throws SystemErrorException
     */
    private function ifNoProducts(PDO $conn, int $id): bool
    {
        try {
            $sql = "select id from product where category_id = $id";

            $result = $conn->query($sql);

            $fetchedResult = $result->fetchAll();

            if ($fetchedResult) {
                $this->validationErrors[] = 'Cannot delete a category that has products associated with it. Delete the products first';
            }

            return $this->validationErrors ? false : true;

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function updateCategory(PDO $conn): void
    {
        try {
            $sql = "update category
                    set parent_id = :parentId,
                    title = :title,
                    description = :description
                    where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

            $this->fillCategoryStatement($statement);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }

    }

    /**
     * @return bool
     */
    public function validateCategory(): bool
    {
        if (!$this->title) {
            $this->validationErrors[] = 'Please enter a title';
        }

        if ($this->id === $this->parentId) {
            $this->validationErrors[] = 'The parent category cannot be the same as the current category';
        }

        return $this->validationErrors ? false : true;
    }

    /**
     * @param array $data
     */
    public function fillCategoryObject(array $data): void
    {
        $this->parentId = $data['parent'];

        $this->title = $data['title'];

        $this->description = $data['description'];
    }

    /**
     * @param PDOStatement $statement
     */
    private function fillCategoryStatement(PDOStatement $statement): void
    {
        $statement->bindValue(':title', $this->title, PDO::PARAM_STR);

        $statement->bindValue(':parentId', $this->parentId, PDO::PARAM_STR);

        if (!$this->description) {
            $statement->bindValue(':description', $this->description, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':description', $this->description, PDO::PARAM_STR);
        }
    }

    /**
     * @param array $image
     * @return bool
     */
    public function validateCategoryImage(array $image): bool
    {
        $extensions = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

        if ($image['size'] > 1000000) {
            $this->imageValidationErrors[] = 'A file size can be 1 Mb max';
        }

        if (!in_array($image['type'], $extensions)) {
            $this->imageValidationErrors[] = 'The file is not an image. Eligible extensions: png, jpeg, jpg, gif';
        }

        return $this->imageValidationErrors ? false : true;
    }

    /**
     * @param PDO $conn
     * @param array $image
     * @return bool
     * @throws SystemErrorException
     */
    public function updateCategoryImage(PDO $conn, array $image): bool
    {
        global $ROOT;

        $pathInfo = pathinfo($image['name']);

        $base = $pathInfo['filename'];

        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base); // replace all except a-z, A-Z, 0-9, _, -

        $fileName = $base . '.' . $pathInfo['extension'];

        $imageUploadDestination = '/upload/category/' . $fileName;

        for ($i = 1; file_exists($ROOT . $imageUploadDestination); $i++) {
            $imageUploadDestination = '/upload/category/' . $base . '-' . $i . '.' . $pathInfo['extension'];
        }

        $isFileMoved = move_uploaded_file($image['tmp_name'], $ROOT . $imageUploadDestination);

        if (!$isFileMoved) {
            return false;
        }

        $previousImage = $this->image;

        $this->image = $imageUploadDestination;

        $this->setCategoryImage($conn);

        if ($previousImage) {
            unlink($ROOT . $previousImage);
        }

        return true;
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    private function setCategoryImage(PDO $conn): void
    {
        try {
            $sql = "update category set image = :image where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

            $statement->bindValue(':image', $this->image, PDO::PARAM_STR);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @throws SystemErrorException
     */
    static public function deleteCategoryImage(PDO $conn, int $id): void
    {
        try {
            $sql = "update category set image = null where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @throws SystemErrorException
     */
    public function createCategory(PDO $conn): void
    {
        try {
            $sql = "insert into category (parent_id, title, description)
                    values (:parentId, :title, :description)";

            $statement = $conn->prepare($sql);

            $this->fillCategoryStatement($statement);

            $statement->execute();

            $this->id = $conn->lastInsertId();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return int
     * @throws SystemErrorException
     */
    public static function getParentCategory(PDO $conn, int $id): int
    {
        try {
            $sql = "select parent_id from category where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $parameters
     * @param string $where
     * @return int
     * @throws SystemErrorException
     */
    public static function countCategoriesFiltered(PDO $conn, array $parameters, string $where): int
    {
        try {
            $sql = "select count(distinct c.id)
                    from category c
                    where c.root_men_category = 0 
                    and c.root_women_category = 0 
                    %s";

            $newSQL = sprintf($sql, $where);

            $statement = self::prepareFilterStatement($conn, $parameters, $newSQL);

            $statement->execute();

            return $statement->fetchColumn();

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

    /**
     * @param PDO $conn
     * @param array $parameters
     * @param string $newSQL
     * @return PDOStatement
     */
    private static function prepareFilterStatement(PDO $conn, array $parameters, string $newSQL): PDOStatement
    {
        $statement = $conn->prepare($newSQL);

        foreach ($parameters as $key => $value) {
            if ($key === 'ids') {
                $statement->bindValue(':ids', implode(',', $value), PDO::PARAM_STR);
            }
        }

        return $statement;
    }

    /**
     * @param PDO $conn
     * @param array $parameters
     * @param string $where
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws SystemErrorException
     */
    public static function getPageOfCategoriesFiltered(PDO $conn, array $parameters, string $where, int $limit, int $offset): array
    {
        try {
            $sql = "select distinct c.id, 
                    c.title, 
                    c.parent_id as parentId, 
                    pc.title as parentTitle,
                    pc1.title as rootCategory 
                    from category c 
                    left join category pc 
                    on pc.id = c.parent_id 
                    left join category pc1 
                    on pc1.id = pc.parent_id
                    where c.root_men_category = 0 
                    and c.root_women_category = 0 
                    %s
                    order by c.parent_id asc, 
                    c.id asc
                    limit :limit 
                    offset :offset";

            $newSQL = sprintf($sql, $where);

            $statement = self::prepareFilterStatement($conn, $parameters, $newSQL);

            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);

            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS, Category::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }
    }

}