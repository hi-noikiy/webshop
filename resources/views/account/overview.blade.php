@extends('master', ['pagetitle' => 'Account / Overzicht'])

@section('title')
        <h3>Account <small>overzicht</small></h3>
@stop

@section('content')
        <div class="row">
                <div class="col-md-3">
                        @include('account.sidebar')
                </div>
                <div class="col-md-9">
                        <table class="table">
                                <tr>
                                        <td><b>Inlognaam</b></td>
                                        <td>{{ Auth::user()->login }}</td>
                                </tr>
                                <tr>
                                        <td><b>Bedrijf</b></td>
                                        <td>{{ Auth::user()->company }}</td>
                                </tr>
                                <tr>
                                        <td><b>Correspondentie adres</b></td>
                                        <td>{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                        <td><b>Aantal bestellingen</b></td>
                                        <td>{{ $orderCount }}</td>
                                </tr>
                        </table>
                </div>
        </div>
@stop
