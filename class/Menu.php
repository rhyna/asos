<?php


class Menu
{
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
}