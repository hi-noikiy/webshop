<div class="row" id="cart-item-container">
    @if ($quote->getItemCount() > 0)
        <div id="cart-overlay">
            <i class="fa fa-3x fa-spinner fa-spin"></i>
        </div>

        @include('checkout.cart.header')

        @foreach($quote->getItems() as $item)
            @include('checkout.cart.item')
        @endforeach

        @include('checkout.cart.footer')
    @else
        <div class="col-sm-6 col-sm-offset-3">
            <div class="alert alert-warning">
                {{ __('It looks like your cart is still empty.') }}
            </div>
        </div>
    @endif
</div>