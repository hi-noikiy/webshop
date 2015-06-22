@extends('master')

@section('title')
        <h3>Downloads</h3>
@stop

@section('content')
        <div class="row">
                <div class="col-md-6">
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        <h3 class="panel-title">Catalogus</h3>
                                </div>
                                <div class="panel-body">
                                        <p>
                                                {!! $catalogus->content !!}
                                        </p>
                                </div>
                        </div>

                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        <h3 class="panel-title">Flyers</h3>
                                </div>
                                <div class="panel-body">
                                        <p>
                                                {!! $flyers->content !!}
                                        </p>
                                </div>
                        </div>
                </div>
                <div class="col-md-6">
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        <h3 class="panel-title">Artikel bestanden</h3>
                                </div>
                                <div class="panel-body">
                                        <p>
                                                {!! $artikelbestand->content !!}
                                        </p>
                                </div>
                        </div>
                </div>
        </div>

@stop