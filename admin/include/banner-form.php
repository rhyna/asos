<?php if ($banner->validationError): ?>
    <ul class="entity-form-errors">
        <li><?= $banner->validationError ?></li>
    </ul>
<?php endif; ?>
<?php if ($banner->imageValidationErrors): ?>
    <ul class="entity-form-errors entity-form-errors--image">
        <?php foreach ($banner->imageValidationErrors as $imageError): ?>
            <li><?= $imageError ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data" id="bannerForm" class="entity-form">
    <div class="form-image">
        <div class="form-group">
            <div class="entity-form-image entity-form-image--banner"
                 style="background-image: url('<?= $banner->image ?>')">
            </div>
            <input class="form-control-file" name="image" id="image" type="file">
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
            <option value="" <?= !$banner->bannerPlaceId ? 'selected' : '' ?>>
                NO PLACE (Banner not posted yet)
            </option>
        </select>
    </div>
    <div class="form-group">
        <label for="link">Link</label>
        <input class="form-control" type="text" name="link" id="link"
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
    <button type="submit" class="entity-form-submit" onclick="event.preventDefault();
            updateBanner(<?= $banner->id ?>)">Submit
    </button>
</form>