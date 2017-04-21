cart = {};

var $cartOverlay = $('#cart-overlay');
var $cartBadge = document.getElementById('cart-badge');

/**
 * Destroy the whole cart.
 *
 * @param target
 */
cart.destroy = function (target) {
    event.preventDefault();

    var $target = $(target);
    var url = $target.data('destroy-url');

    axios.delete(url)
        .then(function (response) {
            if (response.data.success) {
                window.location.reload();
            } else {
                notification.show(response.data.message, "danger");
            }
        })
        .catch(function (error) {
            console.log(error);

            notification.show('Er is een probleem opgetreden tijdens het legen van uw winkelwagen.', "danger");
        });
};

/**
 * Update a single item in the cart.
 *
 * @param target
 */
cart.update = function (target) {
    event.preventDefault(target);

    var $target = $(target);
    var $row = $target.parents('.cart-item');
    var url = $target.data('update-url');
    var quantity = $target.val();

    setTimeout(function () {
        if (quantity === $(target).val()) {
            $target.blur();
            $cartOverlay.fadeIn(250);

            axios.patch(url, {
                quantity: quantity
            })
                .then(function (response) {
                    console.log(response);
                    if (response.data.success) {
                        $row.find('.cart-item-subtotal').html(response.data.subtotal);
                        $('.cart-grand-total').html(response.data.total);
                        $target.val(response.data.quantity);

                        notification.show("Uw winkelwagen is aangepast.");
                    } else {
                        notification.show(response.data.message, "danger");
                    }

                    $cartOverlay.fadeOut(250);
                })
                .catch(function (error) {
                    console.log(error);

                    notification.show("Er is een probleem opgetreden tijdens het wijzigen van uw winkelwagen.", "danger");

                    $cartOverlay.fadeOut(250);
                });
        }
    }, 1000);
};

/**
 * Delete a single item from the cart.
 *
 * @param target
 */
cart.delete = function (target) {
    event.preventDefault();

    var $target = $(target);
    var $row = $target.parents('.cart-item');
    var deleteUrl = $target.data('delete-url');

    axios.delete(deleteUrl)
        .then(function (response) {
            if (response.data.success) {
                notification.show(response.data.message);

                if (response.data.count === 0) {
                    window.location.reload();
                }

                $row.remove();
                $cartBadge.textContent = response.data.count;

                footer.autoPosition();
            } else {
                notification.show(response.data.message, "danger");
            }
        })
        .catch(function (error) {
            console.log(error);

            notification.show("Er is een probleem opgetreden tijdens het verwijderen van het product.", "danger");
        });
};

/**
 * Add a product to the cart.
 *
 * @param $target
 */
cart.add = function ($target) {
    event.preventDefault();

    var addUrl = $target.getAttribute('data-add-url');
    var product = $target.getAttribute('data-product-id');
    var quantity = $target.parentNode.parentNode.querySelector('input').value;

    axios.put(addUrl, {
        quantity: quantity,
        product: product
    })
        .then(function (response) {
            if (response.data.success) {
                notification.show(response.data.message);
                $cartBadge.textContent = response.data.count;
            } else {
                notification.show(response.data.message, "danger");
            }
        })
        .catch(function (error) {
            console.log(error);

            notification.show("Er is een probleem opgetreden tijdens het toevoegen van het product.", "danger");
        });
};