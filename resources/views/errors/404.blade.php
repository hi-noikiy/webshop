@extends('errors.master', ['pagetitle' => 'Error / 404'])

@section('title')
    <h3>Error 404: Not Found</h3>
@endsection

@section('content')
    <div class="alert alert-danger" role="alert">
        @if (!$exception->getMessage())
            De opgevraagde pagina <code>{{ request()->getUri() }}</code> is niet gevonden op de server.
        @else
            {{ $exception->getMessage() }}
        @endif
    </div>
@endsection
