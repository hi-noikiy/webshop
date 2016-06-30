@extends('errors.master')

@section('title')
    <h3>Error 404: Not Found</h3>
@endsection

@section('content')
    <div class="alert alert-danger" role="alert">
        {{ $exception->getMessage() }}
    </div>
@endsection