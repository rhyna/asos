<form action="" method="post" enctype="multipart/form-data" id="categoryForm" class="<?= $classMode ?>-category-form">
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
    <div class="form-group">
        <div class="<?= $classMode ?>-category-form-image <?= $category->image ? '' : $classMode . '-category-form-image--deleted' ?>"
            <?= ($category->image ? 'style="background-image: url(' . $category->image . ')"' : '') ?>>
            <?= !$category->image ? 'No image' : '' ?>
        </div>
        <input class="form-control-file" name="image" id="image" type="file">
    </div>
    <div class="form-group <?= $classMode ?>-category-delete-image">
        <?php if ($category->image): ?>
            <button type="button"
                    class="btn btn-danger <?= $classMode ?>-category-delete-image-button"
                    name="delete-image"
                    onclick="">
                Delete image
            </button>
        <?php endif; ?>
    </div>
        <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" type="text" name="description" id="description"
                  rows="5"><?= htmlspecialchars($category->description) ?>
        </textarea>
    </div>
    <button type="submit" class="<?= $classMode ?>-category-submit">Submit</button>
</form>