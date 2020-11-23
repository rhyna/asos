$('.subbar-item').hover(function () {
    $(this).find($('.subbar-dropdown-menu__wrapper')).addClass('subbar-dropdown-menu__wrapper--show');

    $(this).addClass('subbar-item--active');
}, function () {

    $(this).find($('.subbar-dropdown-menu__wrapper')).removeClass('subbar-dropdown-menu__wrapper--show');

    $(this).removeClass('subbar-item--active');
})

$('.product-form-option--disabled').prop('disabled', true);

function deleteProductImage(button) {
    let $button = $(button);

    let productId = $button.data('id');

    let image = $button.data('image')

    let parentImage = $button.closest('.form-image');

    $.ajax({
        url: '/admin/delete-product-image.php',
        type: 'POST',
        data: {
            id: productId,
            image: image,
        },
    })
        .done(function (response) { // when the server code is 200
            $(parentImage).find(".edit-product-form-image")
                .html('No image')
                .addClass('edit-product-form-image--deleted')
                .css('background-image', '');
            $($button).addClass('edit-product-delete-image-button--deleted');
        })
        .fail(function (response) { // when the server code is other than 200
            alert(response.responseText);
        })
}

function passEntityId(entityId) {
    let modalHiddenInput = $('#deleteEntity').find('.delete-entity-modal-entity-id');

    modalHiddenInput.val(entityId);
}

function deleteEntity(form) {
    let url = form.action;

    let id = $('#deleteEntity').find('.delete-entity-modal-entity-id')[0].defaultValue;

    let onDeletionModal = $('#onDeletionResponse');

    let entityType = '';

    if (url.includes('product')) {
        entityType = 'products';
    } else if (url.includes('category')) {
        entityType = 'categories';
    }

    let a = $('.all' + entityType + '-list-item').find("a[href*='id=" + id + "']");

    let listItem = a.closest('.all' + entityType + '-list-item');

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            id: id,
        },
    })
        .done(function (response) {
            $('#deleteEntity').modal('hide');

            onDeletionModal.find('.modal-title').html('');

            onDeletionModal.find('.modal-body').html(response);

            onDeletionModal.modal('show');

            listItem.remove();
        })
        .fail(function (response) {
            $('#deleteEntity').modal('hide');

            onDeletionModal.find('.modal-title').html('An error occurred');

            onDeletionModal.find('.modal-body').html(response.responseText);

            onDeletionModal.modal('show');
        })
}