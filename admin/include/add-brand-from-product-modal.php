<?php
/**
 * @var Product $product
 * @var string $mode
 */

?>

<div class="modal fade add-brand-modal" id="addBrand" tabindex="-1" role="dialog"
     aria-labelledby="addBrandLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                        action="/admin/add-brand-from-product-action.php"
                        method="post"
                        id="brandFormProduct"
                        onsubmit="event.preventDefault(); addBrandFromProduct(this);">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title"
                               value="" required>
                    </div>
                    <div class="form-group">
                        <label for="descriptionWomen">Description (women)</label>
                        <textarea class="form-control" name="descriptionWomen" id="descriptionWomen"
                                  rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="descriptionMen">Description (men)</label>
                        <textarea class="form-control" name="descriptionMen" id="descriptionMen"
                                  rows="5"></textarea>
                    </div>
                    <input type="hidden" value="<?= $product->id ?>" name="productId">
                    <?php if ($mode === 'edit-product'): ?>
                        <input type="hidden" value="edit" name="productMode">
                    <?php elseif ($mode === 'add-product'): ?>
                        <input type="hidden" value="add" name="productMode">
                    <?php endif; ?>
                    <button
                            type="submit"
                            class="btn btn-primary primary-button entity-modal-submit">
                        Submit
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>