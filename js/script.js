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

function passProductId(productId) {
    let modalHiddenInput = $('#deleteProduct').find('.delete-product-modal-product-id');

    modalHiddenInput.val(productId);
}