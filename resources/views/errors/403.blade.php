@extends('errors.master', ['pagetitle' => 'Error / 403'])

@section('title')
        <h3>Error 403: Forbidden</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                U heb bent niet bevoegd om deze pagina te bekijken.<br />
                <br />
                Mogelijke oorzaken zijn:<br />
                <br />
                <ul>
                        <li>Geen administrator</li>
                </ul>
        </div>
@stop
