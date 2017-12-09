<hr />

@foreach ($results->get('products') as $product)
    <div class="product-listing" id="product-{{ $product->getAttribute('id') }}">
        <div class="row">
            <div class="col-1">
                <div class="product-thumbnail">
                    <img src="{{ $product->getImageUrl() }}" class="img-thumbnail">
                </div>
            </div>

            <div class="col-8">
                <h5>
                    <a href="{{ routeIf('catalog.product', ['sku' => $product->getAttribute('sku')]) }}">
                        {{ $product->getAttribute('name') }}
                    </a>
                </h5>

                <div class="row">
                    <div class="col-8">
                        <small>{{ __('Artikelnummer') }}: {{ $product->getAttribute('sku') }}</small> <br />
                        <small class="product-path">{{ $product->getPath() }}</small>
                    </div>
                </div>
            </div>

            <div class="col-3 text-right">
                @auth
                    <price :product="{{ $product }}"></price>
                @endauth
            </div>
        </div>
    </div>

    <hr />
@endforeach
