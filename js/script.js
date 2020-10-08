$('.subbar-item').hover(function () {
    $(this).find($('.subbar-dropdown-menu__wrapper')).addClass('subbar-dropdown-menu__wrapper--show');
    $(this).addClass('subbar-item--active');
}, function () {
    $(this).find($('.subbar-dropdown-menu__wrapper')).removeClass('subbar-dropdown-menu__wrapper--show');
    $(this).removeClass('subbar-item--active');
})

