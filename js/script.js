$('.subbar-item').hover(function () {
    $(this).find($('.subbar-dropdown-menu__wrapper')).addClass('subbar-dropdown-menu__wrapper--show');

    $(this).addClass('subbar-item--active');
}, function () {

    $(this).find($('.subbar-dropdown-menu__wrapper')).removeClass('subbar-dropdown-menu__wrapper--show');

    $(this).removeClass('subbar-item--active');
})

$('.entity-form-option--disabled').prop('disabled', true);

function deleteEntityImage(buttonElement) {
    let button = $(buttonElement);

    let entityType = button.data('type');

    let entityId = button.data('id');

    let imageName = button.data('image');

    let image = button.closest('.form-image');

    $.ajax({
        url: '/admin/delete-' + entityType + '-image.php',
        type: 'POST',
        data: {
            id: entityId,
            image: imageName,
        },
    })
        .done(function (response) {
            $(image).find(".entity-form-image")
                .html('No image')
                .addClass('entity-form-image--deleted')
                .css('background-image', '');
            $(button).addClass('entity-form-delete-image-button--deleted');
        })
        .fail(function (response) {
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

    let a = $('.entity-list-item').find("a[href*='id=" + id + "']");

    let listItem = a.closest('.entity-list-item');

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

function updateBanner(id) {
    let currentBannerId = id;

    let selectedBannerPlace = $('select[name="banner-place"]').val();

    let bannerPlaceModal = $('.banner-place-modal');

    $.ajax({
        url: '../admin/taken-banner-places.php',
        type: 'GET',
    })
        .done(function (response) {
            let takenBannerPlaces = JSON.parse(response);

            let needToShowModal = false;

            takenBannerPlaces.forEach(function (item) {
                if (Number(item.bannerPlaceId) === Number(selectedBannerPlace) && Number(item.bannerId) !== Number(currentBannerId)) {
                    needToShowModal = true;
                }
            })

            if (needToShowModal) {
                bannerPlaceModal.modal('show');

                bannerPlaceModal.find('.submit-banner-data').on('click',
                    function () {
                        $('#bannerForm').submit();

                    })

            } else {
                $('#bannerForm').submit();
            }

        })

        .fail(function (response) {
            alert(response.responseText);
        })

}

function addBrandFromProduct(form) {
    let url = form.action;

    let productId = $('.add-brand-modal').find("input[name='productId']").val();

    let productMode = $('.add-brand-modal').find("input[name='productMode']").val();

    let title = $('.add-brand-modal').find("input[name='title']").val();

    let descriptionWomen = $('.add-brand-modal').find("textarea[name='descriptionWomen']").val();

    let descriptionMen = $('.add-brand-modal').find("textarea[name='descriptionMen']").val();

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            productId: productId,
            productMode: productMode,
            title: title,
            descriptionWomen: descriptionWomen,
            descriptionMen: descriptionMen,
        },
    })
        .done(function (response) {
            $('#addBrand').modal('hide');

            let newOption = JSON.parse(response);

            let option = $("<option></option>");

            option.val(newOption['id']).html(newOption['title']);

            $('select#brand').append(option);

            $('option[value=' + newOption['id'] + ']').attr('selected', 'selected');

        })
        .fail(function (response) {
            alert(response);
        })
}

function showSizes() {
    let selectedOptionId = $('#categoryId').val();

    let selectedSizeList = $('#productSizes').val();

    let selectedSizes = JSON.parse(selectedSizeList);

    $.ajax({
        url: '../admin/get-product-sizes.php',
        type: 'POST',
        data: {
            categoryId: selectedOptionId,
        },
    })
        .done(function (response) {
            let sizes = JSON.parse(response);

            $('.product-size-list__content').remove();

            let content = "<div class='product-size-list__content'></div>";

            $('.product-size-list').append(content);

            if (sizes.length === 0) {
                $('.product-size-list-empty').addClass('product-size-list-empty--show')
            } else {
                $('.product-size-list-empty').removeClass('product-size-list-empty--show');
            }

            sizes.forEach(function (size) {

                size['id'] = Number(size['id']);

                let productSizeItem = "<div class='product-size-item' data-id='" + size['id'] + "'>";

                $('.product-size-list__content').append(productSizeItem);

                let currentItem = $('.product-size-item[data-id=' + size['id'] + ']');

                let checkbox = '<input type="checkbox" class="form-control" name="sizes[]" id="size" value="' + size['id'] + '">';

                currentItem.append(checkbox);

                let label = '<label for="size">' + size['title'] + '</label>';

                currentItem.append(label);

                selectedSizes.forEach(function (selectedSize){
                    if (selectedSize === size['id']) {
                        currentItem.find("input[type='checkbox']").prop('checked', true);
                    }
                })
            })
        })
        .fail(function (response) {
            alert(response);
        })
}

if ($('.product-form #categoryId').length) {
    showSizes();
}

