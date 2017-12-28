@extends('errors.master')

@section('title')
    <h3>Error 410: Gone</h3>
@endsection

@section('content')
    <div class="alert alert-danger" role="alert">
        {{ $exception->getMessage() }}
    </div>
@endsection