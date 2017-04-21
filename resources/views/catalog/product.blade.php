@extends('layouts.main', ['pagetitle' => 'Catalog / '.$product->getName()])

@section('title')
    <h3>{{ $product->name }}</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4" id="image">
            <div class="well well-lg text-center">
                <img src="{{ $product->getImage() }}" alt="{{ $product->getImage() }}" class="product-image">
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
                                <b>Actie</b>: <span class="price">{{ app('format')->price($product->getPrice(false)) }}</span>
                            @else
                                <b>Bruto</b>: <span class="price">{{ app('format')->price($product->getPrice(false)) }}</span>
                                <br/>
                                <b>Netto</b>: <span class="price">{{ app('format')->price($product->getPrice(true)) }}</span>
                            @endif
                        </div>

                        <br />

                        <form action="{{ route('checkout::cart.add') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <input type="hidden" name="product" value="{{ $product->getId() }}" />

                            <div class="input-group add-to-cart col-sm-4">
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

                        <br />

                        <button class="btn btn-default col-sm-4" onclick="favorites.toggle(this)" id="favorite-button"
                                data-check-url="{{ route('customer::account.favorites::check', ['product' => $product->getId()]) }}">
                            <span class="fa fa-fw fa-heart"></span> <span id="favorite-button-text"></span>
                        </button>
                    </div>
                </div>

                <hr />
            @endif

            {{--@if (count($pack_list) >= 1)--}}
                {{--<div class="alert alert-warning text-center">--}}
                    {{--<h3>Attentie!</h3>--}}
                    {{--<p>--}}
                        {{--Dit product is onderdeel van 1 of meer actiepakketten: <br />--}}
                        {{--@foreach($pack_list as $pack)--}}
                            {{--<a href="/product/{{ $pack->product_number }}">{{ $pack->product->name }}</a><br />--}}
                        {{--@endforeach--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--@endif--}}

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
                        <td>{{ $product->getSku() }}</td>
                    </tr>
                    <tr>
                        <td><b>Product groep</b></td>
                        <td>{{ $product->getGroup() }}</td>
                    </tr>
                    <tr>
                        <td><b>Fabrieksnummer</b></td>
                        <td>{{ $product->getAlternateSku() }}</td>
                    </tr>
                    @if ($product->getEan() != "")
                        <tr>
                            <td><b>EAN</b></td>
                            <td>{{ $product->getEan() }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><b>Voorraad</b></td>
                        <td>{{ app('helper')->stockCode($product->getStockCode()) }}</td>
                    </tr>
                    <tr>
                        <td><b>Merk</b></td>
                        <td>{{ $product->getBrand() }}</td>
                    </tr>
                    <tr>
                        <td><b>Serie</b></td>
                        <td>{{ $product->getSeries() }}</td>
                    </tr>
                    <tr>
                        <td><b>Type</b></td>
                        <td>{{ $product->getType() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        {{--@if ($product->isPack())--}}
            {{--<div class="col-md-12">--}}
                {{--<hr />--}}

                {{--<h2>Artikelen in dit actiepakket</h2>--}}

                {{--<table class="table">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th></th>--}}
                        {{--<th>Omschrijving</th>--}}
                        {{--<th>Aantal</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--@foreach(\App\Models\Pack::where('product_number', $product->number)->first()->products as $pack_product)--}}
                        {{--<tr>--}}
                            {{--<td class="product-thumbnail">--}}
                                {{--<img src="/img/products/{{ $pack_product->details->image }}">--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<a href="/product/{{ $pack_product->details->number }}">{{ $pack_product->details->name }}</a>--}}
                            {{--</td>--}}
                            {{--<td>{{ $pack_product->amount }}</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
        {{--@endif--}}

        {{--@if ($related_products[0] !== null)--}}
            {{--<div class="col-md-12">--}}
                {{--<hr />--}}

                {{--<h2>Verwante artikelen</h2>--}}

                {{--<table class="table">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th></th>--}}
                        {{--<th>Omschrijving</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--@foreach($related_products as $relatedProduct)--}}
                        {{--<tr>--}}
                            {{--<td class="product-thumbnail"><img src="/img/products/{{ $relatedProduct->image }}"></td>--}}
                            {{--<td>--}}
                                {{--<a href="/product/{{ $relatedProduct->number }}?ref=/product/{{ $product->number }}">{{ $relatedProduct->name }}</a>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
        {{--@endif--}}
    </div>
@endsection

@section('extraJS')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            favorites.check();
        });
    </script>
@endsection