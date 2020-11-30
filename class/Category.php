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
     */

    static public function getCategory(PDO $conn, int $id): ?Category
    {
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
     * @throws Exception
     */
    static public function getCategories(PDO $conn, int $parentId): array
    {
        $sql = "select title, id from category where parent_id = $parentId";

        $result = $conn->query($sql);

        $fetchedResult = $result->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($fetchedResult) && $fetchedResult) {
            return $fetchedResult;
        } else {
            throw new Exception('The category list is empty or a fetch error occurred');
        }
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
     */
    static public function getRootCategories(PDO $conn): void
    {
        $rootCategoriesSQL = 'select title, id from category where parent_id IS NULL';

        $rootCategories = $conn->query($rootCategoriesSQL);

        self::$categoryLevels = $rootCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param PDO $conn
     */
    static public function getFirstLevelCategories(PDO $conn): void
    {
        foreach (self::$categoryLevels as &$categoryLevel) {
            $firstLevelCategoriesSQL =
                "select category.title, category.id, category.parent_id, c.title as parent_title
                        from category 
                        left join category c on category.parent_id = c.id
                        where category.parent_id = $categoryLevel[id]";

            $firstLevelCategories = $conn->query($firstLevelCategoriesSQL);

            $categoryLevel['child_category1'] = $firstLevelCategories->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * @param PDO $conn
     */
    static public function getSecondLevelCategories(PDO $conn): void
    {
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
    }

    /**
     * @param PDO $conn
     * @return array
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
     * @throws Exception
     */
    public function deleteCategory(PDO $conn, int $id): bool
    {
        if ($this->isNotParent($conn, $id) && $this->ifNoProducts($conn, $id)) {
            $sql = "delete from category where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            return $statement->execute();
        } else {
            return false;
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     * @throws Exception
     */
    private function isNotParent(PDO $conn, int $id): bool
    {
        $sql = "select id from category where parent_id = $id";

        $result = $conn->query($sql);

        $fetchedResult = $result->fetchAll();

        if (!is_array($fetchedResult)) {
            throw new Exception('A fetch error occurred');
        }

        if ($fetchedResult) {
            $this->validationErrors[] = 'Impossible to delete a category that has child categories';
        }

        return $this->validationErrors ? false : true;
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     * @throws Exception
     */
    private function ifNoProducts(PDO $conn, int $id): bool
    {
        $sql = "select id from product where category_id = $id";

        $result = $conn->query($sql);

        $fetchedResult = $result->fetchAll();

        if (!is_array($fetchedResult)) {
            throw new Exception('A fetch error occurred');
        }

        if ($fetchedResult) {
            $this->validationErrors[] = 'Impossible to delete a category that has products associated with it. Delete the products first';
        }

        return $this->validationErrors ? false : true;
    }

    /**
     * @param PDO $conn
     * @return bool
     */
    public function updateCategory(PDO $conn): bool
    {
        $sql = "update category
        set     parent_id = :parentId,
                title = :title,
                description = :description
        where   id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

        $this->fillCategoryStatement($statement);

        return $statement->execute();

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

        try {
            $errorMessages = [];

            if ($image['size'] > 1000000) {
                $errorMessages[] = 'A file size can be 1 Mb max';
            }

            if (!in_array($image['type'], $extensions)) {
                $errorMessages[] = 'The file is not an image. Eligible extensions: png, jpeg, jpg, gif';
            }

            if ($errorMessages) {
                $errorMessage = implode('<br>', $errorMessages);

                throw new Exception($errorMessage);
            }

        } catch (Exception $e) {
            $imageErrors = $e->getMessage();

            $imageErrors = explode('<br>', $imageErrors);

            $this->imageValidationErrors = $imageErrors;
        }

        return $this->imageValidationErrors ? false : true;
    }

    /**
     * @param PDO $conn
     * @param array $image
     * @return bool
     */
    public function updateCategoryImage(PDO $conn, array $image): bool
    {
        global $ROOT;

        $pathInfo = pathinfo($image['name']);

        $base = $pathInfo['filename'];

        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base); // all except a-z, A-Z, 0-9, _, -

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

        if (!$this->setCategoryImage($conn)) {
            return false;
        }

        if ($previousImage) {
            unlink($ROOT . $previousImage);
        }

        return true;
    }

    /**
     * @param PDO $conn
     * @return bool
     */
    private function setCategoryImage(PDO $conn): bool
    {
        $sql = "update category set image = :image where id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $this->id, PDO::PARAM_INT);

        $statement->bindValue(':image', $this->image, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     */
    static public function deleteCategoryImage(PDO $conn, int $id): bool
    {
        $sql = "update category set image = null where id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * @param PDO $conn
     * @return bool
     */
    public function createCategory(PDO $conn): bool
    {
        $sql = "insert into category (parent_id, title, description)
                values (:parentId, :title, :description)";

        $statement = $conn->prepare($sql);

        $this->fillCategoryStatement($statement);

        if ($statement->execute()) {
            $this->id = $conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }
}