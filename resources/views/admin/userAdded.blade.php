@extends('master', ['pagetitle' => 'Admin / Gebruiker toegevoegd'])

@section('title')
        <h3>Admin <small>gebruiker toegevoegd</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <br />

        <div class="row">
                <div class="col-md-12">
                        <div class="col-md-8 col-md-offset-2">
                                <h4>De gebruiker is aangemaakt met de volgende gegevens:</h4>

                                <ul class="list-group">
                                        <li class="list-group-item">Login: {{ $input['login'] }}</li>
                                        <li class="list-group-item">Naam: {{ $input['name'] }}</li>
                                        <li class="list-group-item">Straat: {{ $input['street'] }}</li>
                                        <li class="list-group-item">Postcode: {{ $input['postcode'] }}</li>
                                        <li class="list-group-item">Plaats: {{ $input['city'] }}</li>
                                        <li class="list-group-item"><b>Wachtwoord: {{ $password }}</b></li>
                                </ul>
                        </div>
                </div>
        </div>

@stop
