@extends('master', ['pagetitle' => 'Admin / Importeren geslaagd'])

@section('title')
        <h3>Admin <small>importeren geslaagd</small></h3>
@endsection

@section('content')
        @include('admin.nav')

        <br />

        <div class="row">
                <div class="col-md-12">
                        <a href="/admin/import"><span class="glyphicon glyphicon-chevron-left"></span> Terug naar de import pagina</a>

                        <div class="text-center">
                                @if (Session::get('type') === 'afbeelding' || Session::get('type') === 'download')
                                        <h4>De {{ Session::get('type') }} import is gelukt. Er {{ (Session::get('count') === 1 ? 'is' : 'zijn') }} {{ Session::get('count') }} {{ Session::get('type') }}{{ (Session::get('count') === 1 ? '' : 'en') }} geimporteerd in {{ Session::get('time') }} seconden.</h4>
                                @else
                                        <h4>De {{ Session::get('type') }} upload is gelukt. De import wordt uitgevoerd over {{ Helper::timeToNextCronJob() }} minuten.</h4>
                                @endif
                        </div>
                </div>
        </div>

@endsection
