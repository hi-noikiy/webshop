@if ($product->isAction())
    <div class="net-price">
        <p>Netto</p>
        <div>&euro;{{ app('format')->price($product->getSpecialPrice()) }}</div>
    </div>
@else
    <div class="gross-price">
        <p>Bruto</p>
        <div>&euro;{{ app('format')->price($product->getPrice(false)) }}</div>
    </div>

    <div class="discount">
        <p>Korting</p>
        <div>{{ $product->getDiscount() }}%</div>
    </div>

    <div class="net-price">
        <p>Netto</p>
        <div>&euro;{{ app('format')->price($product->getPrice(true)) }}</div>
    </div>
@endif