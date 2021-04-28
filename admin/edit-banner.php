<?php

/**
 * @var PDO $conn;
 */

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $banner->fillBannerObject($_POST);

        $banner->validateBanner();

        if ($_FILES['image']['name']) {
            $banner->validateBannerImage($_FILES);
        }

        if (!$banner->validationError && !$banner->imageValidationErrors) {
            $bannerToReplaceId = $banner->placeIdDupes($conn);

            if ($bannerToReplaceId) {
                Banner::replaceBannerPlace($conn, $bannerToReplaceId);
            }

            $banner->updateBanner($conn);

            if (!$banner->updateBannerImage($conn, $_FILES)) {
                throw new Exception('The banner image has not been updated');
            }

            Url::redirect('/admin/banners.php');
        }
    }

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

<?php require_once __DIR__ . '/include/replace-banner-confirmation.php'?>

<?php require_once __DIR__ . '/include/footer.php' ?>
