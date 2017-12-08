let running = false;

$(document).on('price:fetch', function () {
    if (running) {
        return;
    }

    running = true;
    var skus = [];
    var priceElements = $("[id^='price-']");

    priceElements.each(function () {
        var sku = this.id.replace('price-', '');

        skus.push(sku);
    });

    axios.post('/fetchPrice', { skus: skus })
        .then(function (response) {
            var data = response.data;

            data.payload.forEach(function (item) {
                var $product = $('#price-' + item.sku);

                if (!$product) {
                    return;
                }

                $product.find('#gross-price-' + item.sku).html(item.gross_price.toFixed(2));
                $product.find('#net-price-' + item.sku).html(item.net_price.toFixed(2));
                $product.find('.price-per').html('Prijs per ' + item.sales_unit + ' van ' + item.refactor + ' ' + item.price_per);

                $product.removeClass('price-loading').addClass('price-loaded');
            });

            running = false;
        })
        .catch(function (error) {
            console.log(error);

            priceElements.each(function () {
                $(this).html('Geen prijs info beschikbaar');
            });

            running = false;
        });
});