<div class="banner-hotcategorysmall-image"
     style="background-image: url('<?= $banner->image ?>')">
    <a href="<?= $banner->link ?>">
        <img src="<?= $banner->image ?>" alt="">
    </a>
</div>

<div class="banner-hotcategorybig-image"
     style="background-image: url('<?= $banner->image ?>')">
    <a href="<?= $banner->link ?>"></a>
</div>

<?php foreach ($hotCategorySmallBanners as $banner): ?>
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
<?php endforeach; ?>

<ul class="trending-brands__content">
    <?php foreach ($trendingBrands as $brand): ?>
        <li class="trending-brands-item">
            <a href="<?= $brand->link ?>">
                <img src="<?= $brand->image ?>" alt="">
            </a>
        </li>
    <?php endforeach; ?>
</ul>


