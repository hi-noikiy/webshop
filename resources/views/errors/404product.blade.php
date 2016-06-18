@extends('errors.master')

@section('title')
        <h3>Error 404: Not Found</h3>
@endsection

@section('content')
        <div class="alert alert-danger" role="alert">
                Er is geen product gevonden met nummer {{ Session::pull('product_id') }}
        </div>
@endsection