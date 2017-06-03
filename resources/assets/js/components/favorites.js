favorites = {};

/**
 * Check the favorite status of a product
 */
favorites.check = function () {
    var $button = document.getElementById('favorite-button');
    var $buttonText = document.getElementById('favorite-button-text');

    if ($button) {
        var checkUrl = $button.getAttribute('data-check-url');

        axios.post(checkUrl)
            .then(function (response) {
                if (response.data.success) {
                    $button.setAttribute('data-toggle-url', response.data.toggle_url);
                    $buttonText.textContent = response.data.text;
                } else {
                    notification.show(response.data.message, "danger");
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
};

/**
 * Toggle the favorite status of a product.
 *
 * @param $target
 */
favorites.toggle = function ($target) {
    var toggleUrl = $target.getAttribute('data-toggle-url');

    if (toggleUrl) {
        var $button = document.getElementById('favorite-button');
        var $buttonText = document.getElementById('favorite-button-text');

        axios.post(toggleUrl)
            .then(function (response) {
                if (response.data.success) {
                    notification.show(response.data.message);

                    $button.setAttribute('data-toggle-url', response.data.toggle_url);
                    $buttonText.textContent = response.data.text;
                } else {
                    notification.show(response.data.message, "danger");
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
};