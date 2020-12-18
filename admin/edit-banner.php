<?php

require_once __DIR__ . '/include/header.php';

$error = null;

$bannerPlaces = [];

try {
    Auth::ifNotLoggedIn();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new Exception('The id is not provided');
    }

    $id = (int)$id;

    $banner = Banner::getBanner($conn, $id);

    if (!$banner) {
        throw new Exception('Such a banner does not exist');
    }

    $bannerPlaces = BannerPlace::getBannerPlaces($conn);

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<main>
    <div class="container">
        <?php
        if ($error) {
            echo $error;
        } else {
            include_once __DIR__ . '/include/banner-form.php';
        }
        ?>
    </div>
</main>

<?php require_once __DIR__ . '/include/footer.php' ?>
