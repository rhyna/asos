

<form action="" method="post" enctype="multipart/form-data" id="brandForm" class="entity-form">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title" id="title"
               value="<?= htmlspecialchars($brand->title) ?>" required>
    </div>
    <div class="form-group">
        <label for="descriptionWomen">Description (women)</label>
        <textarea class="form-control" name="descriptionWomen" id="descriptionWomen"
                  rows="5"><?= htmlspecialchars($brand->descriptionWomen) ?></textarea>
    </div>
    <div class="form-group">
        <label for="descriptionMen">Description (men)</label>
        <textarea class="form-control" name="descriptionMen" id="descriptionMen"
                  rows="5"><?= htmlspecialchars($brand->descriptionMen) ?></textarea>
    </div>
    <button type="submit" class="entity-form-submit">Submit</button>
</form>