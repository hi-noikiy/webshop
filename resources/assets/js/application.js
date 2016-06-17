// Set the useragent in a data attribute in the html tag
var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    if (location.hash == '#login')
        $('#loginModal').modal('show');
});

$('#loginModal').on('shown.bs.modal', function () {
    $('input[name=username]').focus();
});

setTimeout(function() {
    $('#statusmessage').slideUp();
}, 5000);

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";