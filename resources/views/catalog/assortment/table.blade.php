<div id="catalog-item-container">
    <h4>{{ $products->total() }} producten gevonden</h4>

    <div class="text-center">
        {{ $products->links() }}
    </div>

    @foreach($products as $product)
        @include('catalog.assortment.table.product')
    @endforeach

    <div class="text-center">
        {{ $products->links() }}
    </div>
</div>