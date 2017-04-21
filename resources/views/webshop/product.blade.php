@extends('layouts.main', ['pagetitle' => 'Webshop / Product ' . $product->number])

@section('title')
    <h3>{{ $product->name }}</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4" id="image">
            <div class="well well-lg text-center">
                <img src="{{ asset('img/products/'.$product->image) }}" alt="{{ $product->image }}" class="product-image">
                @if ($product->isAction())
                    <img src="{{ asset('img/actie.png') }}" class="actie-image hidden-xs">
                @endif
            </div>
        </div>
        <div class="col-md-8">
            @if (\Auth::check())
                <div class="row">
                    <div class="col-xs-12">
                        <div class="product-price-block">
                            @if ($product->isAction())
                                <b>Actie</b>: <span class="price">&euro;{{ app('format')->price($product->getPrice(false)) }}</span>
                            @else
                                <b>Bruto</b>: <span class="price">&euro;{{ app('format')->price($product->getPrice(false)) }}</span>
                                <br/>
                                <b>Netto</b>: <span class="price">&euro;{{ app('format')->price($product->getPrice(true)) }}</span>
                            @endif
                        </div>

                        <br />

                        <form action="{{ route('checkout::cart.add') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <input type="hidden" name="product" value="{{ $product->getId() }}" />

                            <div class="input-group col-sm-4">
                                <span class="input-group-addon" id="qty-addon">Aantal</span>
                                <input type="text" class="form-control" name="quantity" value="{{ old('quantity', 1) }}"
                                       placeholder="Aantal" aria-describedby="qty-addon">
                                <span class="input-group-btn">
                                    <button class="btn btn-success" type="submit">
                                        <span><i class="fa fa-fw fa-shopping-cart"></i><b>+</b></span>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br />

                        <button id="favorite-button" class="btn btn-default col-xs-4" type="button"
                                data-product="{{ $product->number }}" data-refresh="false">
                            <span class="fa fa-fw fa-heart"></span> <span id="favorite-button-text"></span>
                        </button>
                    </div>
                </div>

                <hr />
            @endif

            @if (count($pack_list) >= 1)
                <div class="alert alert-warning text-center">
                    <h3>Attentie!</h3>
                    <p>
                        Dit product is onderdeel van 1 of meer actiepakketten: <br />
                        @foreach($pack_list as $pack)
                            <a href="/product/{{ $pack->product_number }}">{{ $pack->product->name }}</a><br />
                        @endforeach
                    </p>
                </div>
            @endif

            <div class="panel panel-primary">
                <div class="panel-heading">
                    Details
                </div>

                @if ($product->description)
                    <div class="panel-body">
                        {!! $product->description->value !!}
                    </div>
                @endif

                <table class="table">
                    <tr>
                        <td><b>Product nummer</b></td>
                        <td>{{ $product->number }}</td>
                    </tr>
                    <tr>
                        <td><b>Product groep</b></td>
                        <td>{{ $product->group }}</td>
                    </tr>
                    <tr>
                        <td><b>Fabrieksnummer</b></td>
                        <td>{{ $product->altNumber }}</td>
                    </tr>
                    @if ($product->ean != "")
                        <tr>
                            <td><b>EAN</b></td>
                            <td>{{ $product->ean }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><b>Voorraad</b></td>
                        <td>{{ app('helper')->stockCode($product->stockCode) }}</td>
                    </tr>
                    <tr>
                        <td><b>Merk</b></td>
                        <td>{{ $product->brand }}</td>
                    </tr>
                    <tr>
                        <td><b>Serie</b></td>
                        <td>{{ $product->series }}</td>
                    </tr>
                    <tr>
                        <td><b>Type</b></td>
                        <td>{{ $product->type }}</td>
                    </tr>
                    <tr>
                        <td><b>Prijs per</b></td>
                        <td>{{ $product->price_per_str }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        @if ($product->isPack())
            <div class="col-md-12">
                <hr />

                <h2>Artikelen in dit actiepakket</h2>

                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Omschrijving</th>
                        <th>Aantal</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Pack::where('product_number', $product->number)->first()->products as $pack_product)
                            <tr>
                                <td class="product-thumbnail">
                                    <img src="/img/products/{{ $pack_product->details->image }}">
                                </td>
                                <td>
                                    <a href="/product/{{ $pack_product->details->number }}">{{ $pack_product->details->name }}</a>
                                </td>
                                <td>{{ $pack_product->amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if ($related_products[0] !== null)
            <div class="col-md-12">
                <hr />

                <h2>Verwante artikelen</h2>

                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Omschrijving</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($related_products as $relatedProduct)
                            <tr>
                                <td class="product-thumbnail"><img src="/img/products/{{ $relatedProduct->image }}"></td>
                                <td>
                                    <a href="/product/{{ $relatedProduct->number }}?ref=/product/{{ $product->number }}">{{ $relatedProduct->name }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@section('extraJS')
    @if (Auth::check())
        <script type="text/javascript">
            var $favoriteButton = $('#favorite-button');
            var $favoriteButtonText = $('#favorite-button-text');
            var productNumber = $favoriteButton.data("product");

            function setFavoriteButtonSuffix(remove) {
                if (remove) {
                    $favoriteButton.data('url', '{{ route("account.favorites::delete") }}');
                    $favoriteButtonText.text("Verwijderen uit favorieten");
                } else {
                    $favoriteButton.data('url', '{{ route("account.favorites::add") }}');
                    $favoriteButtonText.text("Toevoegen aan favorieten");
                }
            }

            function checkFavoriteStatus() {
                $.ajax({
                    url: "{{ route('account.favorites::check') }}",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        product: productNumber
                    },
                    dataType: "json",
                    success: function (data) {
                        setFavoriteButtonSuffix(data.payload.exists);
                        $favoriteButtonText.blur();
                    },
                    error: function () {
                        alert("Er is een fout opgetreden.");
                    }
                });
            }

            checkFavoriteStatus();

            $favoriteButton.click(function () { //The button the the product page and the fav list
                var productNumber = $favoriteButton.data("product");

                $.ajax({
                    url: $favoriteButton.data('url'),
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        product: productNumber
                    },
                    success: function (data) {
                        checkFavoriteStatus();
                    }
                });
            });
        </script>
    @endif
    <script>
        $(document).ready(function () {
            $('#qty').keyup(function () {
                if (isNaN($('#qty').val()) || $('#qty').val() == "") {
                    $('#addToCart').attr("disabled", "disabled");
                } else {
                    $('#addToCart').removeAttr('disabled');
                }
            });
        });
    </script>
@endsection
