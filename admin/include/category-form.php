<?php if ($category->validationErrors): ?>
    <ul class="entity-form-errors">
        <?php foreach ($category->validationErrors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php if ($category->imageValidationErrors): ?>
    <ul class="entity-form-errors entity-form-errors--image">
        <?php foreach ($category->imageValidationErrors as $imageError): ?>
            <li><?= $imageError ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data" id="categoryForm" class="entity-form">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title" id="title"
               value="<?= htmlspecialchars($category->title) ?>">
    </div>
    <div class="form-group">
        <label for="parent">Parent Category</label>
        <select class="form-control" name="parent" id="parent">
            <?php foreach ($categories as $rootCategory): ?>
                <option value="<?= $rootCategory['id'] ?>"
                        style='font-weight: bold;'
                    <?= $category->parentId === $rootCategory['id'] ? 'selected' : '' ?>>
                    <?= $rootCategory['title'] ?>
                </option>
                <?php foreach ($rootCategory['child_category1'] as $firstLevelCategory): ?>
                    <option value="<?= $firstLevelCategory['id'] ?>"
                        <?= $category->parentId === $firstLevelCategory['id'] ? 'selected' : '' ?>>
                        -- <?= $firstLevelCategory['title'] ?>
                    </option>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-image">
        <div class="form-group">
            <div class="entity-form-image entity-form-image--category <?= $category->image ? '' : 'entity-form-image--deleted' ?>"
                <?= ($category->image ? 'style="background-image: url(' . $category->image . ')"' : '') ?>>
                <?= !$category->image ? 'No image' : '' ?>
                <?php if ($category->image): ?>
                    <button type="button"
                            class="entity-form-delete-image-button"
                            name="delete-image"
                            data-id="<?= $category->id ?>"
                            data-image="<?= $category->image ?>"
                            data-type="category"
                            onclick="deleteEntityImage(this)">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
            </div>
            <input class="form-control-file" name="image" id="image" type="file">
        </div>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" type="text" name="description" id="description"
                  rows="5"><?= htmlspecialchars($category->description) ?></textarea>
    </div>
    <button type="submit" class="entity-form-submit">Submit</button>
</form>