<?php


class Category
{
    public static $categoryLevels = [];

    static public function getRootWomenCategoryId($conn)
    {
        $sql = 'select id from category where root_women_category = 1';
        $result = $conn->query($sql);
        return $result->fetchColumn();
    }

    static public function getRootMenCategoryId($conn)
    {
        $sql = 'select id from category where root_men_category = 1';
        $result = $conn->query($sql);
        return $result->fetchColumn();
    }

    static public function getCategories($conn, $parentId)
    {
        $sql = "select title, id from category where parent_id = $parentId";
        $result = $conn->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    static public function getSubCategories($conn, $categories)
    {
        $subCategories = [];

        foreach ($categories as $category) {
            $sql = "select * from category where parent_id = " . $category['id'];

            $result = $conn->query($sql);

            $subCategories[$category['title']] = $result->fetchAll(PDO::FETCH_ASSOC);
        }

        return $subCategories;
    }

    static public function getPreviewSubCategories($subCategories)
    {
        return $previewSubCategories = array_slice($subCategories, 0, 2);
    }

    static public function getRootCategories($conn)
    {
        $rootCategoriesSQL = 'select title, id from category where parent_id IS NULL';

        $rootCategories = $conn->query($rootCategoriesSQL);

        self::$categoryLevels = $rootCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function getFirstLevelCategories($conn)
    {
        foreach (self::$categoryLevels as &$categoryLevel) {
            $firstLevelCategoriesSQL =
                "select title, id, parent_id from category where parent_id = $categoryLevel[id]";

            $firstLevelCategories = $conn->query($firstLevelCategoriesSQL);

            $categoryLevel['child_category1'] = $firstLevelCategories->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    static public function getSecondLevelCategories($conn)
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

    static public function getCategoryLevels($conn)
    {
        Category::getRootCategories($conn);

        Category::getFirstLevelCategories($conn);

        Category::getSecondLevelCategories($conn);

        return self::$categoryLevels;
    }
}