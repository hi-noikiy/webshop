<div class="col-xs-12">
    <div class="row cart-item">
        <div class="col-xs-10 col-sm-6">
            <div class="cart-item-name">
                <a href="{{ route('catalog::product', ['sku' => $item->getSku()]) }}">
                    {{ $item->getName() }}
                </a>
            </div>
        </div>

        <div class="col-xs-1 col-sm-1 col-sm-push-5">
            <div class="cart-item-delete text-right">
                <button type="submit" class="btn btn-link" onclick="cart.delete(this)"
                        data-delete-url="{{ route('checkout::cart.delete', ['item' => $item->getId()]) }}">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="col-xs-4 col-sm-2 col-sm-pull-1">
            <div class="cart-item-price text-left">
                {{ app('format')->price($item->getPrice()) }}
            </div>
        </div>

        <div class="col-xs-4 col-sm-1 col-sm-pull-1">
            <div class="cart-item-qty text-right">
                <input type="number" class="form-control" placeholder="Aantal" min="1" step="1"
                       value="{{ $item->getQuantity() }}" oninput="cart.update(this)"
                       data-update-url="{{ route('checkout::cart.update', ['item' => $item->getId()]) }}" />
            </div>
        </div>

        <div class="col-xs-4 col-sm-2 col-sm-pull-1">
            <div class="cart-item-subtotal text-right">
                {{ app('format')->price($item->getSubtotal()) }}
            </div>
        </div>
    </div>
</div>