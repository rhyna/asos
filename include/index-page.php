<?php

/**
 * @var PDO $conn;
 * @var string $gender;
 */

require_once __DIR__ . '/header.php';

$error = '';

$hotCategorySmallBanners = [];

$hotCategoryBigBanners = [];

$trendingBrands = [];

try {
    $rawBanners = Banner::getAllBannersByGender($conn, $gender);

    $banners = Banner::getFormattedBannersByGender($conn, $rawBanners);

    $hotCategorySmallBanners = Banner::getHotCategorySmallBannersByGender($conn, $banners);

    $hotCategoryBigBanners = Banner::getHotCategoryBigBannersByGender($conn, $banners);

    $trendingBrands = Banner::getTrendingBrandsByGender($conn, $banners);

} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>

<?php if ($error): ?>
    <p class="error-message"><?= $error ?></p>
<?php else: ?>
    <main class="main-content">
        <?php if (isset($banners['big_top_banner'])): ?>
            <div class="banner-bigtop">
                <a href="<?= $banners['big_top_banner']->link ?>">
                    <img src="<?= $banners['big_top_banner']->image ?>" alt="">
                    <div class="banner-bigtop-label">
                        <?php if ($banners['big_top_banner']->title): ?>
                            <div class="banner-bigtop-title">
                                <?= $banners['big_top_banner']->title ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($banners['big_top_banner']->buttonLabel): ?>
                            <div class="banner-bigtop-button">
                                <?= $banners['big_top_banner']->buttonLabel ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        <?php endif; ?>
        <?php if ($hotCategorySmallBanners): ?>
            <div class="banner-hotcategorysmall__wrapper">
                <div class="row">
                    <?php foreach ($hotCategorySmallBanners as $banner): ?>
                        <div class="col-xl-3 col-md-4 col-sm-6 col-12">
                            <div class="banner-hotcategorysmall">
                                <a href="<?= $banner->link ?>">
                                    <img src="<?= $banner->image ?>" alt="">
                                </a>
                                <div class="banner-hotcategorysmall-title">
                                    <?= $banner->title ?>
                                </div>
                                <div class="banner-hotcategorysmall-description">
                                    <?= $banner->description ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($banners['full_width_banner'])): ?>
            <div class="banner-fullwidth"
                 style="background-image: url('<?= $banners['full_width_banner']->image ?>')">
                <a href="<?= $banners['full_width_banner']->link ?>">
                    <div class="banner-fullwidth-title__wrapper">
                        <h1 class="banner-fullwidth-title"><?= $banners['full_width_banner']->title ?></h1>
                    </div>
                    <div class="banner-fullwidth-description"><?= $banners['full_width_banner']->description ?></div>
                </a>
            </div>
        <?php endif; ?>
        <?php if ($hotCategoryBigBanners): ?>
            <div class="banner-hotcategorybig__wrapper">
                <div class="row">
                    <?php foreach ($hotCategoryBigBanners as $banner): ?>
                        <div class="col-12 col-sm-6">
                            <div class="banner-hotcategorybig">
                                <a href="<?= $banner->link ?>">
                                    <img src="<?= $banner->image ?>" alt="">
                                </a>
                                <div class="banner-hotcategorybig-title">
                                    <?= $banner->title ?>
                                </div>
                                <div class="banner-hotcategorybig-description">
                                    <?= $banner->description ?>
                                </div>
                                <div class="banner-hotcategorybig-button">
                                    <a href="<?= $banner->link ?>">
                                        <?= $banner->buttonLabel ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($trendingBrands): ?>
            <div class="trending-brands">
                <h2 class="trending-brands-title">
                    Trending brands
                </h2>
                <div class="trending-brands__content">
                    <div class="row">
                        <?php foreach ($trendingBrands as $brand): ?>
                            <div class="col-md-2 col-4">
                                <div class="trending-brands-item">
                                    <a href="<?= $brand->link ?>">
                                        <img src="<?= $brand->image ?>" alt="">
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>

