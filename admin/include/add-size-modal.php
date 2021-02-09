<div class="modal fade add-size-modal" id="addSize" tabindex="-1" role="dialog"
     aria-labelledby="addSizeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add size</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                        action="/admin/add-size-action.php"
                        method="post"
                        id="addSizeForm"
                        onsubmit="event.preventDefault(); addSize(this);">
                    <div class="form-group">
                        <label for="categoryId--addSize">Category</label>
                        <input type="hidden" id="categoryId--addSize" name="categoryId--addSize" value="">
                        <div id="categoryTitle--addSize"></div>
                        <div class="existing-size-warning existing-size-warning--add">Such a size already exists in this category</div>
                    </div>
                    <div class="form-group">
                        <label for="size--addSize">Size</label>
                        <input class="form-control" type="text" name="size--addSize" id="size--addSize"
                               value="" required>
                    </div>
                    <button type="submit"
                            class="btn btn-primary primary-button add-brand-modal-submit">
                        Submit
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>