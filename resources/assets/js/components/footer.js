footer = {};

var fixedFooter = false;

/**
 * Adjust the footer position state and content padding
 */
footer.autoPosition = function () {
    var $footer = document.getElementById('footer');
    var contentHeight = document.body.clientHeight;
    var windowHeight = window.innerHeight - (fixedFooter ? $footer.scrollHeight : 0);

    if (windowHeight > contentHeight) {
        fixedFooter = true;
        $footer.classList.add('footer-fixed-bottom');
    } else {
        fixedFooter = false;
        $footer.classList.remove('footer-fixed-bottom');
    }
};

window.addEventListener('resize', footer.autoPosition);

setInterval(footer.autoPosition, 100);
footer.autoPosition();