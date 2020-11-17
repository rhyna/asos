<?php


class Category
{
    public static $categoryLevels = [];

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
                "select title, id, parent_id from category where parent_id = $categoryLevel[id]";

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
                    "select title, id, parent_id from category
                    where parent_id = $childCategory[id]";

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
}