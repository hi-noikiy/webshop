@extends('master')

@section('title')
        <h3>Admin <small>importeren geslaagd</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <div class="row">
                <div class="col-md-12">
                        <a href="/admin/import"><span class="glyphicon glyphicon-chevron-left"></span> Terug naar de import pagina</a>

                        <div class="text-center">
                                <h4>De {{ Session::get('type') }} import is gelukt. Er zijn {{ Session::get('count') }} regels geimporteerd.</h4>
                        </div>
                </div>
        </div>

@stop