@extends('layouts.main')

@section('title', __('Product - :product', ['product' => $product->getAttribute('sku')]))

@section('content')
    <hr />

    <div class="container">
        <div class="row">
            <div class="col-4" id="image">
                <div class="text-center">
                    <img src="{{ $product->getImageUrl() }}" alt="{{ $product->getAttribute('name') }}" class="img-thumbnail">
                </div>
            </div>

            <div class="col-8">
                <h4>{{ $product->getAttribute('name') }}</h4>

                <hr />

                @auth
                    <div class="row">
                        <div class="col-6">
                            <price :product="{{ $product }}"></price>

                            <br />

                            <div class="row">
                                <div class="col-10">
                                    <add-to-cart sku="{{ $product->getAttribute('sku') }}"
                                                 sales-unit-single="{{ ucfirst(unit_to_str($product->getAttribute('sales_unit'), false)) }}"
                                                 sales-unit-plural="{{ ucfirst(unit_to_str($product->getAttribute('sales_unit'))) }}"
                                                 submit-url="{{ route('checkout.cart') }}"></add-to-cart>
                                </div>

                                <div class="col-2">
                                    <favorites-toggle-button sku="{{ $product->getAttribute('sku') }}"
                                                      check-url="{{ route('favorites.check') }}"
                                                      toggle-url="{{ route('favorites.toggle') }}"></favorites-toggle-button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth

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
            </div>
        </div>

        <br />

        <div class="row">
            <div class="col-4">

            </div>

            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        {{ __("Product details") }}
                    </div>

                    {{--@if ($product->description)--}}
                    {{--<div class="panel-body">--}}
                    {{--{!! $product->description->value !!}--}}
                    {{--</div>--}}
                    {{--@endif--}}

                    <table class="table">
                        <tr>
                            <td><b>{{ __("Product nummer") }}</b></td>
                            <td>{{ $product->getAttribute('sku') }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __("Product groep") }}</b></td>
                            <td>{{ $product->getAttribute('group') }}</td>
                        </tr>
                        {{--<tr>--}}
                        {{--<td><b>Fabrieksnummer</b></td>--}}
                        {{--<td>{{ $product->getAlternateSku() }}</td>--}}
                        {{--</tr>--}}
                        @if ($product->getAttribute('ean'))
                            <tr>
                                <td><b>{{ __("EAN") }}</b></td>
                                <td>{{ $product->getAttribute('ean') }}</td>
                            </tr>
                        @endif
                        {{--<tr>--}}
                        {{--<td><b>Voorraad</b></td>--}}
                        {{--<td></td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td><b>{{ __("Merk") }}</b></td>
                            <td>{{ $product->getAttribute('brand') }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __("Serie") }}</b></td>
                            <td>{{ $product->getAttribute('series') }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __("Type") }}</b></td>
                            <td>{{ $product->getAttribute('type') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <br />
    </div>
@endsection