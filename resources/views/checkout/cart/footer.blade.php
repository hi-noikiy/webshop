<div class="col-xs-12">
    <div class="row cart-footer">
        <div class="col-xs-7 col-sm-9">
            <div class="cart-grand-total-text text-right">
                <b>{{ __('checkout::cart.grandtotal') }}</b><br />
                <span class="small">{{ __('checkout::cart.excluding_tax') }}</span>
            </div>
        </div>

        <div class="col-xs-5 col-sm-2">
            <div class="cart-grand-total text-right">
                {{ app('format')->price($quote->getGrandTotal(true)) }}
            </div>
        </div>
    </div>

    <div class="row cart-footer-buttons">
        <div class="col-xs-12 col-sm-4">
            <button class="btn btn-danger" onclick="cart.destroy(this)" data-destroy-url="{{ route('checkout::cart.destroy') }}">
                <i class="fa fa-trash-o" aria-hidden="true"></i> {{ __('checkout::cart.destroy') }}
            </button>
        </div>

        <div class="col-xs-12 col-sm-8 text-right">
            <br class="visible-xs" />

            <a class="btn btn-primary" href="{{ session('checkout.continue') ?: url('webshop') }}">
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> {{ __('checkout::cart.continue') }}
            </a>

            <a class="btn btn-success">
                {{ __('checkout::cart.finish') }} <i class="fa fa-arrow-circle-right fa-fw" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>