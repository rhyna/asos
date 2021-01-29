<div class="modal fade edit-size-modal" id="editSize" tabindex="-1" role="dialog"
     aria-labelledby="editSizeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit size</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                        action="/admin/edit-size-action.php"
                        method="post"
                        id="editSizeForm"
                        onsubmit="event.preventDefault(); editSize(this);">
                    <div class="form-group">
                        <label for="categoryId">Category</label>
                        <input type="hidden" id="categoryId" name="categoryId" value="">
                        <div id="categoryTitle"></div>
                        <div class="existing-size-warning">Such a size already exists in this category</div>
                    </div>
                    <div class="form-group">
                        <label for="size">Size</label>
                        <input class="form-control" type="text" name="size" id="size"
                               value="" required>
                    </div>
                    <button type="submit"
                            class="btn btn-primary add-brand-modal-submit">
                        Submit
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>