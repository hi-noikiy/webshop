
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('./components/content');
require('./components/footer');
require('./components/cart');
require('./components/search');
require('./components/notification');
require('./components/favorites');
require('./components/address');

var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    if (location.hash === '#login') {
        $('#loginModal').modal('show');
    }

    // $('.notification').on('click', function() {
    //     hideNotification();
    // });
});

$('#loginModal').on('shown.bs.modal', function () {
    $('input[name=company]').focus();
});

// function hideNotification() {
//     var n = $('.notification');
//     var h = $(n).height();
//
//     $(n).animate({
//         top: h - 100 + "px"
//     }, {
//         duration: 500,
//         done: function() {
//             $(n).hide();
//         }
//     });
// }

// setTimeout(function() {
//     hideNotification();
// }, 5000);

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";