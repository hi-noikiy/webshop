@extends('errors.master')

@section('title')
        <h3>Error 404: Not Found</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                De opgevraagde pagina, {{ $_SERVER['REQUEST_URI'] }}, kan niet worden gevonden.
        </div>
@stop