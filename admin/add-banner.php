<?php

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
                if (!Banner::replaceBannerPlace($conn, $bannerToReplaceId)) {
                    throw new Exception('The banner has not been replaced');
                }
            }

            if (!$banner->createBanner($conn, $_FILES)) {
                throw new Exception('The banner has not been created');
            }

            if (!Banner::uploadBannerImage($conn, $_FILES)) {
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


