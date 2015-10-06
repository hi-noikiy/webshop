@extends('errors.master', ['pagetitle' => 'Error / 400'])

@section('title')
        <h3>Error 400: Bad Request</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                De opgevraagde pagina mist parameters, controleer de URL en probeer opnieuw.<br />
                <br />
                Mogelijke oorzaken zijn:<br />
                <br />
                <ul>
                	<li>Geen product nummer opgegeven.</li>
                	<li>Interne fouten</li>
                </ul>
        </div>
@stop
