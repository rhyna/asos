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
                        <ul class="error-warning error-warning--edit"></ul>
                    </div>
                    <div class="form-group">
                        <label for="size--editSize">Size</label>
                        <input class="form-control" type="text" name="size--editSize" id="size--editSize"
                               value="" required>
                    </div>
                    <div class="form-group">
                        <label for="sortOrder--editSize">Sorting number</label>
                        <input type="number" class="form-control" name="sortOrder--editSize" id="sortOrder--editSize">
                    </div>
                    <button type="submit"
                            class="btn btn-primary primary-button entity-modal-submit">
                        Submit
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>