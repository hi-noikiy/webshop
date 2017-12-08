@extends('layouts.main')

@section('title', __('Zoekresultaten'))

@section('content')
    <h2 class="text-center block-title">{{ __('Zoekresultaten') }}</h2>

    <hr />

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form>
                    <div class="input-group">
                        <input name="query" type="text" class="form-control" placeholder="{{ __('Zoeken') }}" value="{{ request('query') }}">
                        <span class="input-group-btn"><button class="btn btn-outline-primary" type="button"><i class="fa fa-fw fa-search"></i></button></span>
                    </div>

                    <hr />

                    @include('components.catalog.filters')
                </form>
            </div>

            <div class="col-md-9">
                @if ($results->get('products')->isEmpty())
                    <div class="alert alert-warning">
                        {{ __("Geen resultaten gevonden voor ':query'", ['query' => request('query')]) }}
                    </div>
                @else
                    @include('components.catalog.products')
                @endif

                <div class="my-3">
                    {{ $results->get('products')->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraJS')
    <script>
        var skus = [];
        var priceElements = $("[id^='price-']");

        priceElements.each(function () {
            var sku = this.id.replace('price-', '');

            skus.push(sku);
        });

        axios.post('{{ routeIf('catalog.fetchPrice') }}', { skus: skus })
            .then(function (response) {
                var data = response.data;

                data.payload.forEach(function (item) {
                    var $product = $('#price-' + item.sku);

                    if (!$product) {
                        return;
                    }

                    $product.find('#gross-price-' + item.sku).html(item.gross_price);
                    $product.find('#net-price-' + item.sku).html(item.net_price);

                    $product.removeClass('price-loading').addClass('price-loaded');
                });
            })
            .catch(function (error) {
                console.log(error);

                priceElements.each(function () {
                    $(this).html('Geen prijs info beschikbaar');
                });
            });
    </script>
@endsection
