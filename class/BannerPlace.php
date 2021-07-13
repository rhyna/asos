<?php


class BannerPlace
{
    public $id;
    public $title;
    public $alias;
    public $gender;

    /**
     * @param PDO $conn
     * @return array
     * @throws SystemErrorException
     */
    public static function getBannerPlaces(PDO $conn): array
    {
        try {
            $sql = "select * from banner_place order by title";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_CLASS, BannerPlace::class);

        } catch (Throwable $e) {
            throw new SystemErrorException();
        }


    }
}