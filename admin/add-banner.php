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

<div class="modal fade banner-place-modal" id="bannerPlace" tabindex="-1" role="dialog"
     aria-labelledby="bannerPlaceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The selected banner place is already taken by another banner.</p>
                <p>Do you want to replace it?</p>
                <p>The banner to be replaced will be marked as "not posted yet".</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, close</button>
                <button type="button" class="btn btn-primary submit-banner-data">
                    Yes, replace
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php' ?>


