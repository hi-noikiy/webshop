@extends('master')

@section('title')
        <h3>Admin <small>gebruiker toegevoegd</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <br />

        <div class="row">
                <div class="col-md-12">
                        <a href="/admin/usermanager"><span class="glyphicon glyphicon-chevron-left"></span> Terug naar de gebruikers beheer pagina</a>

                        <div class="text-center">
                                De gebruiker is aangemaakt met de volgende gegevens: <br />

                                <ul>
                                        @foreach($input as $field)
                                                <li>{{ $field }}</li>
                                        @endforeach
                                        <li>Wachtwoord: {{ $password }}</li>
                                </ul>
                        </div>
                </div>
        </div>

@stop