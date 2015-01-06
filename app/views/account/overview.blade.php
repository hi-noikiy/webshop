@extends('master')

@section('title')
        <h3>Account <small>overzicht</small></h3>
@stop

@section('content')
        <div class="row">
                <div class="col-md-3">
                        @include('account.sidebar')
                </div>
                <div class="col-md-9">
                        <table class="table table-striped">
                                <tr>
                                        <td><b>Inlognaam</b></td>
                                        <td>{{ Auth::user()->login }}</td>
                                </tr>
                                <tr>
                                        <td><b>Bedrijf</b></td>
                                        <td>{{ Auth::user()->company }}</td>
                                </tr>
                                <tr>
                                        <td><b>Gekoppelde E-Mail adres</b></td>
                                        <td>{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                        <td><b>Aantal bestellingen</b></td>
                                        <td>0</td>
                                </tr>
                        </table>
                </div>
        </div>
@stop