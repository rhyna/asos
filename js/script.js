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
            alert(response.responseText);
        })
}

function showSizes() {
    let productCategoryId = $('#categoryId').val();

    let productSizeList = $('#productSizes').val();

    let productSizes = JSON.parse(productSizeList);

    $.ajax({
        url: '../admin/get-product-sizes.php',
        type: 'POST',
        data: {
            categoryId: productCategoryId,
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

                let sizeItem = "<div class='product-size-item' data-id='" + size['id'] + "'>";

                $('.product-size-list__content').append(sizeItem);

                let currentItem = $('.product-size-item[data-id=' + size['id'] + ']');

                let checkbox = '<input type="checkbox" class="form-control" name="sizes[]" id="size-' + size['id'] + '" value="' + size['id'] + '">';

                currentItem.append(checkbox);

                let label = '<label for="size-' + size['id'] + '">' + size['title'] + '</label>';

                currentItem.append(label);

                productSizes.forEach(function (productSize) {
                    if (productSize === size['id']) {
                        currentItem.find("input[type='checkbox']").prop('checked', true);
                    }
                })
            })
        })
        .fail(function (response) {
            alert(response.responseText);
        })
}

if ($('.product-form #categoryId').length) {
    showSizes();

    $(document).on('change', '.product-size-item input[type="checkbox"]', function () {
        let checkedSizes = $('.product-size-item input[type="checkbox"]:checked');

        let ids = [];

        checkedSizes.each(function (key, item) {
            if ($(item).is(':checked')) {
                ids.push(Number($(item).val()));
            }
        });

        $('#productSizes').val(JSON.stringify(ids));
    })
}

function createSizeItem(size) {
    let listItemWrapper = $('<div class="entity-list-item__wrapper"></div>');

    listItemWrapper.appendTo('.entity-list-content');

    let listItem = $('<div class="entity-list-item"></div>');

    listItem.appendTo(listItemWrapper);

    let listItemRow = $('<div class="row entity-list-item__row"></div>');

    listItemRow.appendTo(listItem);

    let listItemCol = $('<div class="col"></div>');

    listItemCol.appendTo(listItemRow);

    let sizeItem = $("<div class='size-item' data-id='" + size['id'] + "'>" + size['title'] + "</div>");

    sizeItem.appendTo(listItemCol);

    let icons = $('<div class="col-1 entity-list-item-icons"></div>');

    icons.appendTo(listItemRow);

    let iconsInner = $('<div class="entity-list-item-icons__inner">');

    iconsInner.appendTo(icons);

    let editButton = $('<button ' +
        'type="button" ' +
        'data-toggle="modal" ' +
        'data-target="#editSize" ' +
        'onclick="passSizeTitle(\'' + size['title'] + '\')">');

    editButton.appendTo(iconsInner);

    let editIcon = $('<i class="far fa-edit"></i>');

    editIcon.appendTo(editButton);

    let deleteButton = $('<button type="button">');

    deleteButton.appendTo(iconsInner);

    let deleteIcon = $('<i class="far fa-trash-alt"></i>');

    deleteIcon.appendTo(deleteButton);
}

function manageSizes() {
    let productCategoryId = $('#categoryId option:selected').val();

    let productCategoryTitle = $('#categoryId option:selected').text().trim().replace('-- ', '');

    $('.add-size-modal input#categoryId').val(productCategoryId);

    $('.add-size-modal #categoryTitle').html(productCategoryTitle);

    if (!productCategoryId) {
        $('.product-size-list-empty').addClass('product-size-list-empty--show')

        return;

    } else {
        $('.product-size-list-empty').removeClass('product-size-list-empty--show');
    }

    $.ajax({
        url: '../admin/manage-sizes.php',
        type: 'POST',
        data: {
            categoryId: productCategoryId,
        },
    })
        .done(function (response) {
            let sizes = JSON.parse(response);

            $('.entity-list-content').remove();

            let listContent = $('<div class="entity-list-content"></div>');

            listContent.appendTo('.entity-list--size');

            $('.add-size-button').addClass('add-size-button--show');

            sizes.forEach(function (size) {
                size['id'] = Number(size['id']);

                createSizeItem(size);
            })
        })
        .fail(function (response) {
            alert(response.responseText);
        })
}

if ($('.size-form #categoryId').length) {
    manageSizes();
}

function addSize(form) {
    let categoryId = $('.add-size-modal input#categoryId').val();

    let size = $('.add-size-modal #size').val();

    $.ajax({
        url: form.action,
        type: 'POST',
        data: {
            categoryId: categoryId,
            size: size,
        },
    })
        .done(function (response) {
            let addedSize = JSON.parse(response);

            let itemExists = $('.size-item[data-id=' + addedSize['id'] + ']').length;

            if (!itemExists) {
                $('.existing-size-warning').removeClass('existing-size-warning--show');

                $('.add-size-modal').modal('hide');

                createSizeItem(addedSize);

            } else {
                $('.existing-size-warning').addClass('existing-size-warning--show');
            }
        })
        .fail(function (response) {
            alert(response.responseText);
        })
}

function passSizeTitle(sizeTitle) {
    $('#editSizeForm input#size').val(sizeTitle);
}


