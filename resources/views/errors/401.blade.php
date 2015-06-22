@extends('errors.master')

@section('title')
        <h3>Error 401: Unauthorized</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                Uw account is niet bevoegd om deze pagina te bekijken.<br />
                <br />
                Mogelijke oorzaken zijn:<br />
                <br />
                <ul>
                        <li>Niet ingelogd</li>
                        <li>De pagina is nog niet actief</li>
                </ul>
        </div>
@stop