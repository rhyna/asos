<?php


class Brand
{
    public $id;
    public $title;
    public $description;

    static public function getAllBrands ($conn) {
        $sql = "select title, id from brand order by title asc";
        $result = $conn->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}