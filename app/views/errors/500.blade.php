@extends('errors.master')

@section('title')
        <h3>Error 500</h3>
@stop

@section('content')
        <div class="alert alert-danger" role="alert">
                Er is een fout opgetreden waardoor de pagina niet kon worden geladen.<Br />
                Wij zijn er van op de hoogte er zullen het probleem zo spoedig mogelijk verhelpen.
        </div>

        <h3>Error bericht: </h3>

        <blockquote>
                <p>{{ nl2br($exception->getMessage()) }} in {{ nl2br($exception->getFile()) }}:{{ $exception->getLine() }}</p>
        </blockquote>
@stop

@section('extraCSS')
        <style type="text/css">
                blockquote {
                        text-transform: capitalize;
                }
        </style>
@stop
