@extends('errors.master', ['pagetitle' => 'Error / 500'])

@section('title')
    <h3>Error 500: Internal Server Error</h3>
@endsection

@section('content')
    <div class="alert alert-danger" role="alert">
        Er is een fout opgetreden waardoor de pagina niet kon worden geladen.<Br />
        Wij zijn er van op de hoogte er zullen het probleem zo spoedig mogelijk verhelpen.<br />
    </div>
@endsection
