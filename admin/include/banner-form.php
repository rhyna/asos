<?php //if ($category->validationErrors): ?>
<!--    <ul class="entity-form-errors">-->
<!--        --><?php //foreach ($category->validationErrors as $error): ?>
<!--            <li>--><? //= $error ?><!--</li>-->
<!--        --><?php //endforeach; ?>
<!--    </ul>-->
<?php //endif; ?>
<?php //if ($category->imageValidationErrors): ?>
<!--    <ul class="entity-form-errors entity-form-errors--image">-->
<!--        --><?php //foreach ($category->imageValidationErrors as $imageError): ?>
<!--            <li>--><? //= $imageError ?><!--</li>-->
<!--        --><?php //endforeach; ?>
<!--    </ul>-->
<?php //endif; ?>

<form action="" method="post" enctype="multipart/form-data" id="bannerForm" class="entity-form">
    <div class="form-image">
        <div class="form-group">
            <div class="entity-form-image entity-form-image--banner <?= $banner->image ? '' : 'entity-form-image--deleted' ?>"
                <?= ($banner->image ? 'style="background-image: url(' . $banner->image . ')"' : '') ?>>
                <?= !$banner->image ? 'No image' : '' ?>
            </div>
            <input class="form-control-file" name="image" id="image" type="file">
            <?php if ($banner->image): ?>
                <button type="button"
                        class="entity-form-delete-image-button--banner"
                        name="delete-image"
                        data-id="<?= $banner->id ?>"
                        data-image="<?= $banner->image ?>"
                        data-type="banner"
                        onclick="deleteEntityImage(this)">
                    Delete image
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label for="banner-place">Banner place</label>
        <select class="form-control" name="banner-place" id="banner-place">
            <?php foreach ($bannerPlaces as $bannerPlace): ?>
                <option value="<?= $bannerPlace->id ?>"
                    <?= $banner->bannerPlaceId === $bannerPlace->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($bannerPlace->title) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="link">Link</label>
        <input class="form-control" type="url" name="link" id="link"
               value="<?= htmlspecialchars($banner->link) ?>">
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title" id="title"
               value="<?= htmlspecialchars($banner->title) ?>">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control"
                  name="description"
                  id="description"
                  rows="3"><?= htmlspecialchars($banner->description) ?></textarea>
    </div>
    <div class="form-group">
        <label for="button-label">Button Label</label>
        <input class="form-control" type="text" name="button-label" id="button-label"
               value="<?= htmlspecialchars($banner->buttonLabel) ?>">
    </div>
    <button type="submit" class="entity-form-submit">Submit</button>
</form>