// Calling the function
$(function() {
    $('.toggle-nav').click(function() {
        toggleNavigation();
        var rank_container = $(".rank-container");
        if (rank_container.css("display") != "none") {
            $(".hide-rank-container").click();
        }
    });
});

$(function() {
    // default open
    $('#container').addClass('display-nav');
    $('#canvas').css('width', '85%');
})

// The toggleNav function itself
function toggleNavigation() {
    if ($('#container').hasClass('display-nav')) {
        // Close Nav
        $('#container').removeClass('display-nav');
        // set convas width
        $('#canvas').css('width', '100%');
        // hide pnotify
        PNotify.removeAll();
    } else {
        // Open Nav
        $('#container').addClass('display-nav');
        // set convas width
        $('#canvas').css('width', '85%');
    }
}


// SLiding codes
$("#toggle > li > div").click(function() {
    if (false == $(this).next().is(':visible')) {
        $('#toggle ul').slideUp();
    }
    var $currIcon = $(this).find("span.the-btn");
    $("span.the-btn").not($currIcon).addClass('fa-plus').removeClass('fa-minus');
    $currIcon.toggleClass('fa-minus fa-plus');
    $(this).next().slideToggle();
    $("#toggle > li > div").removeClass("active");
    $(this).addClass('active');
});