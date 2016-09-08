// Set the useragent in a data attribute in the html tag
var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    if (location.hash == '#login') {
        $('#loginModal').modal('show');
    }

    $('.notification').on('click', function() {
        hideNotification();
    });
});

$('#loginModal').on('shown.bs.modal', function () {
    $('input[name=username]').focus();
});

function hideNotification() {
    var n = $('.notification');
    var h = $(n).height();

    $(n).animate({
        top: h - 100 + "px"
    }, {
        duration: 500,
        done: function() {
            $(n).hide();
        }
    });
}

setTimeout(function() {
    hideNotification();
}, 5000);

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";