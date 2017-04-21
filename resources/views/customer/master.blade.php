@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('customer.components.sidebar')
        </div>
        <div class="col-md-9">
            @yield('customer.content')
        </div>
    </div>
@endsection