$('.subbar-item').hover(function () {
    $(this).find($('.subbar-dropdown-menu__wrapper')).addClass('subbar-dropdown-menu__wrapper--show');
    $(this).addClass('subbar-item--active');
}, function () {
    $(this).find($('.subbar-dropdown-menu__wrapper')).removeClass('subbar-dropdown-menu__wrapper--show');
    $(this).removeClass('subbar-item--active');
})

$('.product-form-option--disabled').prop('disabled', true);

$('.edit-product-delete-image-button').on('click', function () {

    let productId = $(this).data('id');

    let image = $(this).data('image')

    let button = $(this);

    let parentImage = button.closest('.form-image');

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
            $(button).addClass('edit-product-delete-image-button--deleted');
        })
        .fail(function (response) { // when the server code is other than 200
            alert('An error occurred');
        })
})