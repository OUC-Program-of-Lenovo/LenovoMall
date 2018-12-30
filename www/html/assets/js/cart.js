var is_open = false;
$('.hide-cart-container').on('click', function() {
    $(".cart-container").toggle(1000);
    if (is_open) {
        $(this).removeClass('glyphicon-eye-open');
        $(this).addClass('glyphicon-eye-close');
        is_open = false;
    } else {
        $(this).removeClass('glyphicon-eye-close');
        $(this).addClass('glyphicon-eye-open');
        is_open = true;
    }
});

$('.rank-container').optiscroll();