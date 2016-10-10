<hr />

@if ($paginator->total() > 0)
    <div class="alert alert-success" role="alert">
        {{ $paginator->total() }} resultaten gevonden in {{ $scriptTime }} seconden.
    </div>
@else
    <div class="alert alert-warning" role="alert">
        Geen resultaten gevonden.
    </div>
@endif

<hr />

<div class="hidden-sm hidden-xs">
    <div class="text-center">
        {!! $paginator->render() !!}
    </div>

    <hr />
</div>

@foreach($products as $product)
    <div class="product-listing">
        <div class="row">
            <div class="col-sm-2">
                <div class="product-thumbnail">
                    <img src="/img/logo.png{{-- /img/products/{{ $product->image }} --}}" alt="{{ $product->image }}">
                </div>
            </div>

            <div class="col-sm-8">
                <h4><a href="{{ url("/product/{$product->number}") }}">{{ $product->name }}</a></h4>

                <small>Artikelnummer: {{ $product->number }} | {{ ($product->isAction() ? 'Actieproduct' : 'Korting: ' . $product->discount . '%') }}</small>
            </div>

            <div class="col-sm-2 text-right">
                @if(Auth::check())
                    <div class="gross-price">
                        <p>Bruto</p>
                        <div>&euro;{{ $product->real_price }}</div>
                    </div>

                    <div class="net-price">
                        <p>Netto</p>
                        <div>&euro;{{ number_format($product->real_price * ((100-$product->discount) / 100), 2, ".", "") }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach

<hr />

<div class="text-center">
    {!! $paginator->render() !!}
</div>

<script>
    $('.pagination a').on('click', function (e) {
        e.preventDefault();

        window.page = e.target.innerText;

        filter();
    });
</script>