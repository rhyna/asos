<div class="modal fade delete-product-modal" id="deleteProduct" tabindex="-1" role="dialog"
     aria-labelledby="deleteProductLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the product?
            </div>
            <form action="/admin/delete-product.php" method="post">
                <input type="hidden" class="delete-product-modal-product-id" value="" name="id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No, close</button>
                    <button type="submit" class="btn btn-primary">Yes, delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
