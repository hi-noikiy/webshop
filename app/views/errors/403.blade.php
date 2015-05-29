@extends('errors.master')

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
                	<li>Niet ingelogd</li>
                	<li>De pagina is nog niet actief</li>
                </ul>
        </div>
@stop