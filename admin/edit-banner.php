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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $banner->fillBannerObject($_POST);

        $banner->validateBanner();

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

            if (!$banner->updateBanner($conn)) {
                throw new Exception('The banner has not been updated');
            }

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
