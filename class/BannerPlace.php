<?php


class BannerPlace
{
    public $id;
    public $title;
    public $alias;

    /**
     * @param PDO $conn
     * @return array
     */
    public static function getBannerPlaces(PDO $conn): array
    {
        $sql = "select * from banner_place order by title";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_CLASS, BannerPlace::class);
    }
}