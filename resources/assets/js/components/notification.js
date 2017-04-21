notification = {};

var $notificationWrapper = document.getElementById('notification-wrapper');
var $successNotification = document.getElementById('success-notification-template');
var $dangerNotification = document.getElementById('danger-notification-template');

notification.show = function (message, type) {
    if (typeof type === "undefined") {
        type = "success";
    }

    var $notification;

    if (type === "success") {
        $notification = $successNotification.cloneNode();
    } else if (type === "danger") {
        $notification = $dangerNotification.cloneNode();
    }

    $notification.addEventListener('click', function () {
        notification.hide($notification);
    });

    $notification.innerHTML = message;
    $notification.classList.remove('template');
    $notificationWrapper.appendChild($notification);

    setTimeout(function () {
        notification.hide($notification);
    }, 5000);
};

/**
 * Hide the notification
 */
notification.hide = function ($notification) {
    $notification.classList.add('fadeOutLeft');
    $notification.classList.remove('fadeInLeft');
};