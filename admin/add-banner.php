<?php

/**
 * @var PDO $conn;
 */

require_once __DIR__ . '/include/header.php';

try {
    Auth::ifNotLoggedIn();

    $error = null;

    $banner = new Banner();

    $bannerPlaces = BannerPlace::getBannerPlaces($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $banner->fillBannerObject($_POST);

        $banner->validateBanner();

        if (!$_FILES['image']['name']) {
            $banner->imageValidationErrors[] = 'Please upload an image';
        }

        if ($_FILES['image']['name']) {
            $banner->validateBannerImage($_FILES);
        }

        if (!$banner->validationError && !$banner->imageValidationErrors) {
            $bannerToReplaceId = $banner->placeIdDupes($conn);

            if ($bannerToReplaceId) {
                Banner::replaceBannerPlace($conn, $bannerToReplaceId);
            }

            $banner->createBanner($conn, $_FILES);

            if (!Banner::uploadBannerImage($_FILES)) {
                throw new Exception('The banner image has not been uploaded');
            }

            Url::redirect("/admin/edit-banner.php?id=$banner->id");

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


