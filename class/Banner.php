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

    public static function getAllBanners($conn)
    {
        $sql = 'select 
                    banner.id, 
                    banner.banner_place_id as bannerPlaceId,
                    banner.image,
                    banner.link,
                    banner.title,
                    banner.description,
                    banner.button_label as buttonLabel,
                    bp.alias 
                from banner 
                left join banner_place bp 
                on bp.id = banner.banner_place_id';

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_CLASS, 'Banner');

    }

    public static function getFormattedBanners($conn)
    {
        $banners = self::getAllBanners($conn);

        $formattedBanners = [];

        foreach ($banners as $banner) {
            $formattedBanners[$banner->alias] = $banner;
        }
        return $formattedBanners;

    }

    public static function getHotCategorySmallBanners($conn)
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

    public static function getHotCategoryBigBanners($conn)
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

    public static function getTrendingBrands($conn) {
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
}