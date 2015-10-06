@extends('master', ['pagetitle' => 'Account / Favorieten'])

@section('title')
        <h3>Account <small>favorieten</small></h3>
@stop

@section('content')
        <div class="row">
                <div class="col-md-3">
                        @include('account.sidebar')
                </div>
                <div class="col-md-9">
                        @if(count($favorites) === 0)
                                <div class="alert alert-warning text-center">U hebt nog geen favorieten toegevoegd. Favorieten kunnen toegevoegd worden door op de product pagina op de volgende knop te drukken: <button class="btn btn-danger" disabled><span class="glyphicon glyphicon-heart"></span></button></div>
                        @else
                                <?php $counter = 0; //This is used to identify the panels ?>
                                @foreach ($groupData as $group => $productArray)
                                        <div class="panel panel-default">
                                                <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#{{ $counter }}">
                                                                        {{ $group }}
                                                                </a>
                                                        </h4>
                                                </div>
                                                <div id="{{ $counter }}" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                                <table class="table table-striped">
                                                                        <thead>
                                                                        <tr>
                                                                                <th>Product nummer</th>
                                                                                <th>Naam</th>
                                                                                <th class="hidden-xs">Bruto prijs</th>
                                                                                <th class="hidden-xs">Korting</th>
                                                                                <th>Netto prijs</th>
                                                                                <th>Verwijderen</th>
                                                                        </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                                @foreach ($productArray as $product)
                                                                                        <?php
                                                                                                if (isset($discounts[$product->number])) {
                                                                                                        $discount = (double) $discounts[$product->number];
                                                                                                } elseif (isset($discounts[$product->group])) {
                                                                                                        $discount = (double) $discounts[$product->group];
                                                                                                } else {
                                                                                                        $discount = (int) 0;
                                                                                                }

                                                                                                if ($product->special_price === '0.00') {
                                                                                                        $brutoprice 	= (double) number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", "");
                                                                                                        $nettoprice 	= (double) number_format($brutoprice * ((100-$discount) / 100), 2, ".", "");
                                                                                                } else {
                                                                                                        $brutoprice 	= (double) number_format($product->special_price, 2, ".", "");
                                                                                                        $nettoprice 	= (double) $brutoprice;
                                                                                                }

                                                                                                $prijs_per_str	        = ($product->refactor == 1 ? price_per($product->registered_per) : price_per($product->packed_per));
                                                                                        ?>
                                                                                        <tr>
                                                                                                <td>{{ $product->number }}</td>
                                                                                                <td><a href="{{ "/product/" . $product->number }}">{{ $product->name }}</a></td>
                                                                                                <td class="hidden-xs" style="white-space: nowrap;"><span class="glyphicon glyphicon-euro"></span> {{ number_format($brutoprice, 2, ".", "") }}</td>
                                                                                                <td class="hidden-xs">{{ ($product->special_price !== '0.00' ? 'Actie' : $discount . '%') }}</td>
                                                                                                <td style="white-space: nowrap;"><span class="glyphicon glyphicon-euro"></span> {{ number_format($nettoprice, 2, ".", "") }}</td>
                                                                                                <td><button id="changeFav" class="btn btn-danger" title="Verwijder dit product van uw favorieten" data-id="{{ $product->number }}" data-refresh="true"><span class="glyphicon glyphicon-remove"></span></button></td>
                                                                                        </tr>
                                                                                @endforeach
                                                                        </tbody>
                                                                </table>
                                                        </div>
                                                </div>
                                        </div>
                                        <?php $counter++; ?>
                                @endforeach
                        @endif
                </div>
        </div>
@stop
