<?php

$config = require __DIR__ . "/categories-config.php";

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ASOS Home Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/vendor/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="/assets/style/filters/filters.css">
    <link rel="stylesheet" href="/assets/vendor/font/Futura-PT/stylesheet.css">
    <link rel="stylesheet" href="/assets/vendor/fontawesome-free-5.13.1-web/css/all.css">
    <link rel="stylesheet" href="/assets/vendor/product-gallery/stylesheet.css">
    <link rel="stylesheet" href="/assets/vendor/product-gallery/fancybox/jquery.fancybox.css">
    <link rel="stylesheet" href="/assets/style/css/style.css">
</head>
<body>
<header>
    <nav class="topbar">
        <div class="topbar__content">
            <div class="logo">
                <a href="/women.php">
                    <svg class="" width="105" height="30">
                        <path fill="#FFF" fill-rule="evenodd"
                              d="M71.83 21.983c-1.558 1.666-3.56 2.51-5.95 2.51-2.387 0-4.39-.844-5.947-2.51-1.488-1.587-2.343-4.124-2.343-6.96 0-2.766.864-5.27 2.37-6.867 1.572-1.667 3.565-2.516 5.92-2.523 2.36.007 4.35.856 5.924 2.523 1.506 1.598 2.37 4.1 2.37 6.867 0 2.836-.855 5.373-2.343 6.96zm-20.915-6.96c0 .128.005.255.008.38-2.39-2.166-5.845-2.974-7.957-3.394-3.907-.82-6.89-1.58-6.89-4.35 0-1.96 1.757-3.38 5.132-3.14 3.085.224 4.384 2.102 4.74 3.914.05.3.19.515.53.517l5.547.05c.026 0 .048-.003.072-.004-.783 1.816-1.182 3.84-1.182 6.015zM41.48 25.19c-2.683 0-5.64-.95-6.32-4.624-.06-.35-.225-.496-.495-.503l-5.364-.07v-9.446c.71 2.768 3.04 4.684 8.09 5.816 3.38.806 9.24 1.318 9.24 4.774 0 2.408-1.78 4.11-5.15 4.054zm-26.714-.69c-4.327 0-8.29-3.394-8.29-9.47 0-4.77 2.97-9.39 8.32-9.39 2.315 0 8.188.79 8.188 9.39 0 8.62-6.132 9.47-8.218 9.47zm65.922-11.792c1.232 1.636 3.453 2.848 7.063 3.657 3.38.805 9.25 1.318 9.25 4.775 0 2.403-1.78 4.11-5.15 4.05-2.68 0-5.64-.95-6.32-4.625-.052-.35-.22-.497-.49-.504L80.06 20c.523-1.54.79-3.21.79-4.974 0-.793-.056-1.566-.16-2.317zM91.474 30c5.95 0 12.965-2.208 12.416-9.366-.606-6.355-7.244-7.964-10.562-8.625-3.907-.82-6.892-1.58-6.892-4.35 0-1.96 1.758-3.38 5.134-3.14 3.084.224 4.384 2.102 4.74 3.914.05.3.19.515.53.517l5.546.048c.422.002.554-.216.5-.516C101.8 1.874 96.246 0 91.133 0 86.03 0 79.88 1.43 79.443 7.754c-.015.246-.02.486-.02.722-.814-1.683-1.985-3.23-3.495-4.597C73.142 1.37 69.666.03 65.878 0h-.127c-1.81 0-3.58.333-5.26.99a15.26 15.26 0 0 0-4.65 2.888c-1.43 1.295-2.56 2.747-3.36 4.327C51.27 1.822 45.81 0 40.77 0 36.084 0 30.517 1.208 29.3 6.305v-5.06a.49.49 0 0 0-.49-.488h-5.224c-.27 0-.49.22-.49.49V2.61c0 .23-.155.31-.343.175-1.858-1.34-4.607-2.782-7.915-2.782-1.86 0-3.635.326-5.277.968-1.64.65-3.2 1.63-4.64 2.92C3.29 5.37 2.05 7.05 1.23 8.9.417 10.742 0 12.807 0 15.027 0 17.1.367 19.043 1.088 20.8c.722 1.756 1.82 3.382 3.267 4.83 1.446 1.45 3.063 2.553 4.804 3.276 1.74.722 3.66 1.09 5.7 1.09 3.51 0 6.15-1.493 7.88-2.85.19-.144.342-.067.342.17v1.435c0 .27.22.49.49.49H28.8c.27 0 .49-.22.49-.49v-4.83C31.766 29.7 38.04 30 41.113 30c5.137 0 11.06-1.647 12.234-6.7.55.818 1.192 1.597 1.924 2.33 2.8 2.807 6.47 4.316 10.62 4.362h.17c1.97 0 3.87-.377 5.648-1.12a14.82 14.82 0 0 0 4.79-3.242 15.25 15.25 0 0 0 2.594-3.43c1.86 7.438 9.035 7.8 12.387 7.8z"></path>
                    </svg>
                </a>
            </div>
            <ul class="topbar-nav">
                <?php foreach ($config as $configItem): ?>
                    <li class="topbar-nav-item
                        <?php if (strpos($_SERVER['REQUEST_URI'], '/' . $configItem['flag']) !== false
                        || isset($rootCategoryFlag) && $rootCategoryFlag === $configItem['flag']): ?>
                            topbar-nav-item--active
                        <?php endif; ?>">
                        <a href="/<?= $configItem['flag'] ?>.php"><?= $configItem['flag'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form action="/search.php" class="search" method="get">
                <input type="text"
                       placeholder="Search for items, brands, and inspiration"
                       name="query">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
    </nav>
    <div class="subbar__wrapper">
        <ul class="subbar">
            <?php foreach ($config as $configItem): ?>
                <?php if (strpos($_SERVER['REQUEST_URI'], '/' . $configItem['flag']) !== false
                    || isset($rootCategoryFlag) && $rootCategoryFlag === $configItem['flag']): ?>
                    <?php foreach ($configItem['categories'] as $name => $category): ?>
                        <li class="subbar-item">
                            <button type="button" class="subbar-button"><?= $name ?></button>
                            <div class="subbar-dropdown-menu__wrapper">
                                <div class="subbar-dropdown-menu__inner">
                                    <div class="subbar-dropdown-menu subbar-dropdown-menu--product">
                                        <div class="subbar-dropdown-title">
                                            <span>Shop by product</span>
                                        </div>
                                        <div class="subbar-dropdown-menu__content">
                                            <?php foreach ($category as $item): ?>
                                                <a class="subbar-dropdown-item"
                                                   href="/category.php?id=<?= $item['id'] ?>">
                                                    <?= $item['title'] ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="subbar-dropdown-menu subbar-dropdown-menu--brand">
                                        <?php
                                        $ids = [];

                                        foreach ($category as $categoryInfo) {
                                            $ids[] = (int)$categoryInfo['id'];
                                        }

                                        $idsSliced = array_slice($ids, 0, 5);

                                        $brandsInfo = Product::getProductBrandsByCategories($conn, $idsSliced);

                                        $categoryGETParams = '';

                                        foreach ($ids as $id) {
                                            $categoryGETParams .= "&categories[]=$id";
                                        }
                                        ?>
                                        <div class="subbar-dropdown-title">
                                            <span>Shop by brand</span>
                                        </div>
                                        <div class="subbar-dropdown-menu__content">
                                            <ul>
                                                <?php foreach ($brandsInfo as $item): ?>
                                                    <?php if ($item['brand_id']): ?>
                                                        <li class="subbar-dropdown-item--brand">
                                                            <a href="/brand.php?gender=<?= $configItem['flag'] ?>&id=<?= $item['brand_id'] ?><?= $categoryGETParams ?>">
                                                                <div class="subbar-dropdown-item--brand-image"
                                                                     style="background-image: url('<?= $item['image'] ?>')">
                                                                </div>
                                                                <?= $item['title'] ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="subbar-dropdown-menu subbar-dropdown-menu--preview">
                                        <div class="subbar-dropdown-menu-preview__inner">
                                            <?php
                                            $previewSubCategories = Category::getPreviewSubCategories($category);
                                            ?>
                                            <?php foreach ($previewSubCategories as $subCategory): ?>
                                                <div class="subbar-dropdown-menu-preview-image__wrapper">
                                                    <a href="/category.php?id=<?= $subCategory['id'] ?>">
                                                        <div class="subbar-dropdown-menu-preview-image"
                                                             style='background-image: url("<?= $subCategory['image'] ?>")'>
                                                            <span><?= $subCategory['title'] ?></span>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <li class="subbar-item subbar-item--brands">
                        <button type="button" class="subbar-button">Brands</button>
                        <div class="subbar-dropdown-menu__wrapper">
                            <div class="subbar-dropdown-menu__inner">
                                <div class="subbar-dropdown-menu subbar-dropdown-menu--brand">
                                    <div class="subbar-dropdown-title">
                                        <span>All brands</span>
                                    </div>
                                    <div class="subbar-dropdown-menu__content">
                                        <?php
                                        $categoriesForAllBrandsMenu = [];

                                        foreach ($configItem['categories'] as $name => $category) {
                                            for ($i = 0; $i <= 3; $i++) {
                                                if (isset($category[$i])) {
                                                    $categoriesForAllBrandsMenu[] = $category[$i]['id'];
                                                }
                                            }
                                        }
                                        $allBrands = Product::getProductBrandsByCategories($conn, $categoriesForAllBrandsMenu);
                                        ?>
                                        <?php foreach ($allBrands as $brand): ?>
                                            <?php if ($brand['brand_id']): ?>
                                                <a href="/brand.php?gender=<?= $configItem['flag'] ?>&id=<?= $brand['brand_id'] ?>"
                                                   class="subbar-dropdown-item"><?= $brand['title'] ?></a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <a href="/brands.php?gender=<?= $configItem['flag'] ?>"
                                           class="subbar-dropdown-item subbar-dropdown-item--allbrands">All brands</a>
                                    </div>
                                </div>
                                <div class="subbar-dropdown-menu subbar-dropdown-menu--preview">
                                    <div class="subbar-dropdown-menu-preview__inner">
                                        <div class="row subbar-dropdown-menu-preview__inner-row">
                                            <?php
                                            $allBrands = array_splice($allBrands, 0, 4);
                                            ?>
                                            <?php foreach ($allBrands as $brand): ?>
                                                <div class="col-6 subbar-dropdown-menu-preview__inner-col">
                                                    <div class="subbar-dropdown-menu-preview-image__wrapper">
                                                        <a href="/brand.php?gender=<?= $configItem['flag'] ?>&id=<?= $brand['brand_id'] ?>">
                                                            <div class="subbar-dropdown-menu-preview-image"
                                                                 style='background-image:
                                                                         url("<?= $brand['image'] ?>")'
                                                            >
                                                                <span><?= $brand['title'] ?></span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</header>
