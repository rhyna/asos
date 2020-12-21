<?php

class Banner
{
    public $id;
    public $bannerPlaceId;
    public $image;
    public $link;
    public $title;
    public $description;
    public $buttonLabel;
    public $alias;
    public $validationError;

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    public static function getAllBanners(PDO $conn): array
    {
        $sql = 'select 
                    banner.id, 
                    banner.banner_place_id as bannerPlaceId,
                    banner.image,
                    banner.link,
                    banner.title,
                    banner.description,
                    banner.button_label as buttonLabel,
                    bp.alias,
                    bp.title as aliasTitle 
                from banner 
                left join banner_place bp 
                on bp.id = banner.banner_place_id';

        $result = $conn->query($sql);

        $fetchedResult = $result->fetchAll(PDO::FETCH_CLASS, Banner::class);

        if (is_array($fetchedResult) && $fetchedResult) {
            return $fetchedResult;
        } else {
            throw new Exception('The banner list is empty or a fetch error occurred');
        }

    }

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    public static function getFormattedBanners(PDO $conn): array
    {
        $banners = self::getAllBanners($conn);

        $formattedBanners = [];

        foreach ($banners as $banner) {
            $formattedBanners[$banner->alias] = $banner;
        }
        return $formattedBanners;

    }

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    public static function getHotCategorySmallBanners(PDO $conn): array
    {
        $banners = self::getFormattedBanners($conn);

        $hotCategorySmallBanners = [];

        foreach ($banners as $key => $banner) {
            $isHotCategorySmall = strpos($key, 'hot_category_small');

            if ($isHotCategorySmall !== false) {
                $hotCategorySmallBanners[$banner->alias] = $banner;
            };
        }

        return $hotCategorySmallBanners;
    }

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    public static function getHotCategoryBigBanners(PDO $conn): array
    {
        $banners = self::getFormattedBanners($conn);

        $hotCategoryBigBanners = [];

        foreach ($banners as $key => $banner) {
            $isHotCategoryBig = strpos($key, 'hot_category_big');

            if ($isHotCategoryBig !== false) {
                $hotCategoryBigBanners[$banner->alias] = $banner;
            };
        }

        return $hotCategoryBigBanners;
    }

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    public static function getTrendingBrands(PDO $conn): array
    {
        $banners = self::getFormattedBanners($conn);

        $trendingBrands = [];

        foreach ($banners as $key => $banner) {
            $isTrendingBrand = strpos($key, 'trending_brand');

            if ($isTrendingBrand !== false) {
                $trendingBrands[$banner->alias] = $banner;
            };
        }

        return $trendingBrands;
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return Banner|null
     */
    public static function getBanner(PDO $conn, int $id): ?Banner
    {
        $sql = "select 
                    banner.id, 
                    banner.banner_place_id as bannerPlaceId,
                    banner.image,
                    banner.link,
                    banner.title,
                    banner.description,
                    banner.button_label as buttonLabel,
                    bp.alias,
                    bp.title as aliasTitle 
                from banner 
                left join banner_place bp 
                on bp.id = banner.banner_place_id
                where banner.id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_STR);

        $statement->execute();

        return $statement->fetchObject(Banner::class) ?: null;
    }

    /**
     * @param array $data
     */
    public function fillBannerObject(array $data): void
    {
        $this->bannerPlaceId = $data['banner-place'];

        $this->link = $data['link'];

        $this->title = $data['title'];

        $this->description = $data['description'];

        $this->buttonLabel = $data['button-label'];
    }

    /**
     * @return bool
     */
    public function validateBanner(): bool
    {
        if (!$this->link) {
            $this->validationError = 'Please enter a link';
        }

        return $this->validationError ? false : true;

    }

    /**
     * @param PDO $conn
     * @return bool
     */
    public function updateBanner(PDO $conn): bool
    {
        $sql = "update banner 
        set     banner_place_id = :bannerPlaceId, 
                link = :link,
                title = :title,
                description = :description,
                button_label = :buttonLabel
        where id = :id";

        $statement = $conn->prepare($sql);

        $this->fillBannerStatement($statement);

        $statement->bindValue(':id', $this->id, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * @param PDOStatement $statement
     */
    private function fillBannerStatement(PDOStatement $statement): void
    {
        if ($this->bannerPlaceId) {
            $statement->bindValue(':bannerPlaceId', $this->bannerPlaceId, PDO::PARAM_STR);
        } else {
            $statement->bindValue(':bannerPlaceId', $this->bannerPlaceId, PDO::PARAM_NULL);
        }

        $statement->bindValue(':link', $this->link, PDO::PARAM_STR);

        if ($this->title) {
            $statement->bindValue(':title', $this->title, PDO::PARAM_STR);
        } else {
            $statement->bindValue(':title', $this->title, PDO::PARAM_NULL);
        }

        if ($this->description) {
            $statement->bindValue(':description', $this->description, PDO::PARAM_STR);
        } else {
            $statement->bindValue(':description', $this->description, PDO::PARAM_NULL);
        }

        if ($this->buttonLabel) {
            $statement->bindValue(':buttonLabel', $this->buttonLabel, PDO::PARAM_STR);
        } else {
            $statement->bindValue(':buttonLabel', $this->buttonLabel, PDO::PARAM_NULL);
        }
    }

    /**
     * @param PDO $conn
     * @return int
     */
    public function placeIdDupes(PDO $conn): int
    {
        $sql = "select id from banner where banner_place_id = :bannerPlaceId";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':bannerPlaceId', $this->bannerPlaceId, PDO::PARAM_STR);

        $statement->execute();

        return (int)$statement->fetchColumn();
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     */
    public static function replaceBannerPlace(PDO $conn, int $id): bool
    {
        $sql = "update banner set banner_place_id = null where id = :id";

        $statement = $conn->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_STR);

        return $statement->execute();
    }
}