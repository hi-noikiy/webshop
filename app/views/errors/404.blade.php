@extends('master')

@section('title')
        <h3>Error 404</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                De pagina, {{ URI::path() }}, is niet gevonden op de server.
        </div>
@stop