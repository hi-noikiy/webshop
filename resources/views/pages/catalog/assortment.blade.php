@extends('layouts.main')

@section('title', __('Assortiment'))

@section('content')
    <h2 class="text-center block-title">{{ __('Assortiment') }}</h2>

    <hr />

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form action="{{ route('catalog.search') }}">
                    <input type="hidden" name="query" value="{{ request('query') }}">

                    @include('components.catalog.filters')
                </form>
            </div>

            <div class="col-md-9">
                @include('components.catalog.products')

                <div class="my-3 d-none d-sm-block">
                    {{ $results->get('products')->links('pagination::bootstrap-4') }}
                </div>

                <div class="my-3 d-block d-sm-none text-center">
                    {{ $results->get('products')->links('pagination::simple-bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
