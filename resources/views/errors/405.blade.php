@extends('errors.master')

@section('title')
        <h3>Error 405: Method Not Allowed</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                De pagina kan niet worden weergegeven vanwege een niet-toegestaan verzoek.<br />
                <br />
                Mogelijke oorzaken zijn: <Br />
                <ul>
                	<li>Er wordt een GET verzoek gedaan naar een POST url. (of andersom)</li>
                </ul>
        </div>
@stop