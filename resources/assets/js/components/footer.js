footer = {};

var currentPadding = 20;

/**
 * Adjust the footer position state and content padding
 */
footer.autoPosition = function () {
    var $footer = document.getElementById('footer');
    var $content = document.getElementById('content');
    var footerHeight = $footer.offsetHeight;
    var contentHeight = $content.offsetTop + $content.offsetHeight - currentPadding;
    var windowHeight = window.innerHeight;
    var calculatedPadding = (windowHeight - contentHeight - footerHeight);

    if (calculatedPadding < 20) {
        currentPadding = 20;
        $content.style.paddingBottom = 20 + "px";
        $footer.classList.remove('footer-fixed-bottom');
    } else {
        currentPadding = calculatedPadding;
        $content.style.paddingBottom = calculatedPadding + "px";
        $footer.classList.add('footer-fixed-bottom');
    }
};

window.addEventListener('resize', footer.autoPosition);

setInterval(footer.autoPosition, 100);
footer.autoPosition();