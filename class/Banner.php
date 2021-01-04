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
    public $imageValidationErrors = [];

    /**
     * @param PDO $conn
     * @return array
     * @throws Exception
     */
    public static function getAllBanners(PDO $conn): array
    {
        try {
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

            return $result->fetchAll(PDO::FETCH_CLASS, Banner::class);

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
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
     * @throws Exception
     */
    public static function getBanner(PDO $conn, int $id): ?Banner
    {
        try {
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

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }


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
     * @throws Exception
     */
    public function updateBanner(PDO $conn): bool
    {
        try {
            $sql = "update banner 
            set     banner_place_id = :bannerPlaceId, 
                    link = :link,
                    title = :title,
                    description = :description,
                    button_label = :buttonLabel
            where   id = :id";

            $statement = $conn->prepare($sql);

            $this->fillBannerStatement($statement);

            $statement->bindValue(':id', $this->id, PDO::PARAM_STR);

            return $statement->execute();

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }
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
     * @throws Exception
     */
    public function placeIdDupes(PDO $conn): int
    {
        try {
            $sql = "select id from banner where banner_place_id = :bannerPlaceId";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':bannerPlaceId', $this->bannerPlaceId, PDO::PARAM_STR);

            $statement->execute();

            return (int)$statement->fetchColumn();

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }

    }

    /**
     * @param PDO $conn
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public static function replaceBannerPlace(PDO $conn, int $id): bool
    {
        try {
            $sql = "update banner set banner_place_id = null where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_STR);

            return $statement->execute();

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validateBannerImage(array $data): bool
    {
        $image = $data['image'];

        $eligibleExtensions = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ];

        if (!in_array($image['type'], $eligibleExtensions)) {
            $this->imageValidationErrors[] =
                'The file is not an image, eligible extensions are: jpeg, jpg, png, gif';
        }

        if ($image['size'] > 1000000) {
            $this->imageValidationErrors[] =
                'The file size can be 1 Mb max';
        }

        return $this->imageValidationErrors ? false : true;
    }

    /**
     * @param PDO $conn
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateBannerImage(PDO $conn, array $data): bool
    {
        try {
            global $ROOT;

            $image = $data['image']['name'];

            if (!$image) {
                return true;
            }

            $destination = self::prepareBannerImage($data);

            $tempPath = $data['image']['tmp_name'];

            if (!move_uploaded_file($tempPath, $ROOT . $destination)) {
                return false;
            }

            if (!$this->setBannerImage($conn, $destination)) {
                return false;
            }

            if (!$this->image) {
                return true;
            }

            unlink($ROOT . $this->image);

            return true;

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }

    }

    /**
     * @param array $data
     * @return string
     */
    private static function prepareBannerImage(array $data): string
    {
        global $ROOT;

        $image = $data['image']['name'];

        $imageInfo = pathinfo($image);

        $fileName = $imageInfo['filename'];

        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fileName);

        $extension = $imageInfo['extension'];

        $destination = "/upload/banner/$base.$extension";

        for ($i = 1; file_exists($ROOT . $destination); $i++) {
            $destination = "/upload/banner/$base-$i.$extension";
        }

        return $destination;
    }

    /**
     * @param PDO $conn
     * @param string $image
     * @return bool
     * @throws Exception
     */
    private function setBannerImage(PDO $conn, string $image): bool
    {
        try {
            $sql = "update banner set image = :image where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':image', $image, PDO::PARAM_STR);

            $statement->bindValue(':id', $this->id, PDO::PARAM_STR);

            return $statement->execute();

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }
    }

    /**
     * @param PDO $conn
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function createBanner(PDO $conn, array $data): bool
    {
        try {
            $image = self::prepareBannerImage($data);

            $sql = "insert into banner
            (banner_place_id, image, link, title, description, button_label)
            values
            (:bannerPlaceId, :image, :link, :title, :description, :buttonLabel)";

            $statement = $conn->prepare($sql);

            $this->fillBannerStatement($statement);

            $statement->bindValue(':image', $image, PDO::PARAM_STR);

            $statement->execute();

            $this->id = $conn->lastInsertId();

            return true;

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }

    }

    /**
     * @param $data
     * @return bool
     */
    public static function uploadBannerImage($data): bool
    {
        global $ROOT;

        $destination = self::prepareBannerImage($data);

        $tempPath = $data['image']['tmp_name'];

        if (!move_uploaded_file($tempPath, $ROOT . $destination)) {
            return false;
        }

        return true;
    }

    /**
     * @param PDO $conn
     * @param $id
     * @return void
     * @throws Exception
     */
    public function deleteBanner(PDO $conn, $id): void
    {
        try {
            $sql = "delete from banner where id = :id";

            $statement = $conn->prepare($sql);

            $statement->bindValue(':id', $id, PDO::PARAM_STR);

            $statement->execute();

        } catch (Throwable $e) {
            throw new Exception('A system error occurred');
        }
    }
}