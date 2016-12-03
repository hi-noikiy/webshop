// I have no idea what this is, but it's here anyway. Deal with it!
window._ = require('lodash');

// jQuery
window.$ = window.jQuery = require('jquery');

// Random material color generator
window.randomMC = require('random-material-color');
window.randomColor = function () {
    return randomMC.getColor();
};

// Boostrap
require('bootstrap-sass');

// ChartJS
window.Chart = require('chart.js');
window.Chart.defaults.global.maintainAspectRatio = false;

// Names of the months
window.months = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];

$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
    });

    var $notification = $('.notification');

    setTimeout(function () {
        $notification.removeClass('fadeInLeft');
        $notification.addClass('fadeOutLeft');
    }, 5000);

    var $page = $('#page-wrapper');
    var $navToggle = $('#toggle-navigation');

    $navToggle.on('click', function () {
        if ($page.hasClass('show-nav')) {
            $page.removeClass('show-nav');
        } else {
            $page.addClass('show-nav');
        }
    });
});