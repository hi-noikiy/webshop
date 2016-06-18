@extends('errors.master', ['pagetitle' => 'Error / 404'])

@section('title')
        <h3>Error 404: Not Found</h3>
@endsection

@section('content')
        <div class="alert alert-danger" role="alert">
                De opgevraagde pagina, {{ $_SERVER['REQUEST_URI'] }}, kan niet worden gevonden.
        </div>
@endsection
