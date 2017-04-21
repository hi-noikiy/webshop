@foreach($results as $item)
    <div class="suggest-item" onclick="window.location.href = '{{ route('catalog::product', ['product' => $item->getSku()]) }}'">
        <div class="row">
            <div class="col-sm-8">
                <a href="{{ route('catalog::product', ['product' => $item->getSku()]) }}">
                    {{ $item->getName() }}
                </a>

                <br />

                <small>Artikelnummer: {{ $item->getSku() }}</small>
            </div>

            <div class="col-sm-2 col-xs-6 text-right">
                <b>Bruto</b>
                <div class="price">
                    {{ app('format')->price($item->getPrice(false)) }}
                </div>
            </div>

            <div class="col-sm-2 col-xs-6 text-right">
                <b>Netto</b>
                <div class="price">
                    {{ app('format')->price($item->getPrice(true)) }}
                </div>
            </div>
        </div>
    </div>
@endforeach