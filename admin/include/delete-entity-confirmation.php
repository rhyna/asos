<?php

/**
 * @var string $entityType
 */

?>

<div class="modal fade delete-entity-modal" id="deleteEntity" tabindex="-1" role="dialog"
     aria-labelledby="deleteEntityLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the <?= $entityType ?>?
                <?= $entityType === 'size' ? '<br>The size will be deleted only from the current category' : '' ?>
            </div>
            <form action="/admin/delete-<?= $entityType ?>.php" method="post" onsubmit="event.preventDefault(); deleteEntity(this, '<?= $entityType ?>')">
                <input type="hidden" class="delete-entity-modal-entity-id" value="" name="id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No, close</button>
                    <button type="submit" class="btn btn-primary primary-button">Yes, delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
