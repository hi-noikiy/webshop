@extends('errors.master')

@section('title')
        <h3>Error 400: Bad Request</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                De opgevraagde pagina mist parameters, controleer de URL en probeer opnieuw.
        </div>
@stop