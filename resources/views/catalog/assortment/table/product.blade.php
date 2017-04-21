<div class="product-listing">
    <div class="row">
        <div class="col-sm-3 hidden-xs">
            <div class="product-thumbnail">
                <img alt="{{ $product->getName() }}" src="{{ $product->getImage() }}" class="img-responsive" />
            </div>
        </div>

        <div class="col-xs-12 col-sm-9">
            <h4 class="product-name">
                @if ($product->isAction())
                    <span class="label label-success">{{ app('helper')->actionType($product->getActionType()) }}</span>
                @endif
                <a href="{{ route('catalog::product', ['sku' => $product->getSku()]) }}">{{ $product->getName() }}</a>
            </h4>

            <hr />

            <div class="row">
                <div class="col-sm-5">
                    <small>Artikelnummer: {{ $product->getSku() }}</small>
                </div>

                @if (Auth::check())
                    <div class="col-sm-7 text-right">
                        <form action="{{ route('checkout::cart.add') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <input type="hidden" name="product" value="{{ $product->getId() }}" />

                            <div class="input-group add-to-cart">
                                <span class="input-group-addon">Aantal</span>
                                <input type="text" class="form-control" placeholder="Aantal" value="1">
                                <span class="input-group-btn">
                                    <button class="btn btn-success" onclick="cart.add(this)"
                                            data-add-url="{{ route('checkout::cart.add') }}"
                                            data-product-id="{{ $product->getId() }}">
                                        <span><i class="fa fa-fw fa-shopping-cart"></i></span>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <div class="prices">
                            @include('catalog.assortment.table.product.prices')
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>