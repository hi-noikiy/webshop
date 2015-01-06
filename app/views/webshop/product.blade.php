@extends('master')

@section('title')
        <h3>{{ $productData->name }}</h3>
@stop

@section('content')
        <?php
                if ($productData->special_price === '0.00') {
                        $action 		= false;
                        $price 			= (double) number_format((preg_replace("/\,/", ".", $productData->price) * $productData->refactor) / $productData->price_per, 2, ".", "");
                } else {
                        $action 		= true;
                        $discount 		= "Actie";
                        $price 			= (double) number_format(preg_replace("/\,/", ".", $productData->special_price), 2, ".", "");
                }
                $prijs_per_str	                = ($productData->refactor == 1 ? price_per($productData->registered_per) : price_per($productData->packed_per));
        ?>

        @if (Auth::check())
                <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addToCart" aria-hidden="true">
                        <form class="form" action="/cart/add" method="POST">

                                <!-- Non editable form data -->
                                <input class="hidden" name="id" value="{{ $productData->number }}">
                                <input class="hidden" name="name" value="{{ $productData->name }}">
                                <input class="hidden" name="korting" value="{{ $productData->number }}">
                                <input class="hidden" name="price" value="{{ $productData->price }}">

                                <div class="modal-dialog">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">Product toevoegen aan de winkelwagen</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <table class="table">
                                                                <tbody>
                                                                <tr>
                                                                        <td><b>Product nummer</b></td>
                                                                        <td>{{ $productData->number }}</td>
                                                                </tr>
                                                                <tr>
                                                                        <td><b>Naam</b></td>
                                                                        <td>{{ $productData->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                        <td><b>Voorraad</b></td>
                                                                        <td>{{ stockCode($productData->stockCode) }}</td>
                                                                </tr>
                                                                @if (!$action)
                                                                        <tr>
                                                                                <td><b>Bruto prijs per {{ strtolower($prijs_per_str) }}</b></td>
                                                                                <td><span class="glyphicon glyphicon-euro"></span> <span>{{ number_format($price, 2, ".", "") }}</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td><b>Netto prijs per {{ strtolower($prijs_per_str) }}</b></td>
                                                                                <td><span class="glyphicon glyphicon-euro"></span> <span>{{ number_format($price * ((100-$discount) / 100), 2, ".", "") }}</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td><b>Korting</b></td>
                                                                                <td><span>{{ $discount }}</span>%</td>
                                                                        </tr>
                                                                @else
                                                                        <tr>
                                                                                <td><b>Actie prijs per {{ strtolower($prijs_per_str) }}</b></td>
                                                                                <td><span class="glyphicon glyphicon-euro"></span> {{ number_format($price, 2, ".", "") }}</td>
                                                                        </tr>
                                                                @endif
                                                                </tbody>
                                                        </table>
                                                </div>
                                                <div class="modal-footer">
                                                        <div class="row">
                                                                <div class="col-lg-6">
                                                                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Annuleren</button>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                        <div class="input-group">
                                                                                <!-- qrt id is needed for the javascript validation -->
                                                                                <input type="text" class="form-control" placeholder="Aantal" id="qty" name="qty">
                                                                                <span class="input-group-btn">
                                                                                        <!-- Add the product to the cart -->
                                                                                        <button type="submit" class="btn btn-success" id="addToCart" disabled="disabled">Toevoegen</button>
                                                                                </span>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </form>
                </div>
        @endif

        <div class="row">
                <div class="col-md-4" id="image">
                        <div class="well well-lg text-center">
                                <img src="/img/{{ $productData->image }}" alt="{{ $productData->image }}" class="product-image">
                                @if (isset($actie))
                                        <img src="/img/actie.png" class="actie-image hidden-xs">
                                @endif
                        </div>
                </div>
                <div class="col-md-8">
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        @if (Auth::check())
                                                <div class="row">
                                                        <div class="col-sm-6">
                                                                @if (!$action)
                                                                        Bruto prijs: <span class="glyphicon glyphicon-euro"></span> {{ number_format($price, 2, ".", "") }}<br />
                                                                        Netto prijs: <span class="glyphicon glyphicon-euro"></span> {{ number_format($price * ((100-$discount) / 100), 2, ".", "") }}
                                                                @else
                                                                        Actie prijs: <span class="glyphicon glyphicon-euro"></span> {{ number_format($price, 2, ".", "") }}<br />
                                                                @endif
                                                        </div>
                                                        <div class="col-sm-6 text-right">
                                                                <div class="btn-group">
                                                                        <button id="changeFav" class="btn btn-danger changeFav" type="button" data-id="{{ $productData->number }}" data-refresh="false">
                                                                                <span class="glyphicon glyphicon-heart" id="defaultFav"></span>
                                                                                <span class="glyphicon glyphicon-plus" id="addFav" style="display: none;"></span>
                                                                                <span class="glyphicon glyphicon-remove" id="removeFav" style="display: none;"></span>
                                                                                <span class="glyphicon glyphicon-ok" id="okFav" style="display: none;"></span>
                                                                        </button>
                                                                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#addProductModal"><span><div class="glyphicon glyphicon-shopping-cart"></div><b>+</b></span></button>
                                                                </div>
                                                        </div>
                                                </div>
                                        @else
                                                <div class="row">
                                                        <div class="col-sm-6">
                                                                <div class="panel-title">Details</div>
                                                        </div>
                                                        <div class="col-sm-6 text-right">
                                                                <button class="btn btn-success" data-toggle="modal" data-target="#loginModal"><span><div class="glyphicon glyphicon-shopping-cart"></div><b>+</b></span></button>
                                                        </div>
                                                </div>
                                        @endif
                                </div>
                                <div class="panel-body">
                                        <table class="table">
                                                <tr>
                                                        <td><b>Product nummer</b></td>
                                                        <td>{{ $productData->number }}</td>
                                                </tr>
                                                <tr>
                                                        <td><b>Product groep</b></td>
                                                        <td>{{ $productData->group }}</td>
                                                </tr>
                                                <tr>
                                                        <td><b>Fabrieksnummer</b></td>
                                                        <td>{{ $productData->altNumber }}</td>
                                                </tr>
                                                @if ($productData->ean != "")
                                                        <tr>
                                                                <td><b>EAN</b></td>
                                                                <td>{{ $productData->ean }}</td>
                                                        </tr>
                                                @endif
                                                <tr>
                                                        <td><b>Voorraad</b></td>
                                                        <td>{{ stockCode($productData->stockCode) }}</td>
                                                </tr>
                                                <tr>
                                                        <td><b>Merk</b></td>
                                                        <td>{{ $productData->brand }}</td>
                                                </tr>
                                                <tr>
                                                        <td><b>Serie</b></td>
                                                        <td>{{ $productData->series }}</td>
                                                </tr>
                                                <tr>
                                                        <td><b>Type</b></td>
                                                        <td>{{ $productData->type }}</td>
                                                </tr>
                                                <tr>
                                                        <td><b>Prijs per</b></td>
                                                        <td>{{ $prijs_per_str }}</td>
                                                </tr>
                                        </table>
                                </div>
                        </div>
                </div>
        </div>
@stop

@section('extraCSS')
        <style type="text/css">
                .panel-body {
                        padding: 0px !important;
                }
        </style>
@stop

