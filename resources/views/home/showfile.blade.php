@extends('master')

@section('title')
    <h3>{{ $file }}</h3>
@endsection

@section('content')
    <embed src="{{ url('dl/' . $file) }}" class="file-viewer" type='application/pdf'>
@endsection

@section('extraCSS')
    <style>
        .file-viewer {
            width: 100%;
            height: 750px;
        }
    </style>
@endsection