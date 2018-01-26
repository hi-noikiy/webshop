@extends('layouts.main')

@section('title', __('Zoekresultaten'))

@section('content')
    <h2 class="text-center block-title">{{ __('Zoekresultaten') }}</h2>

    <hr />

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form>
                    <input type="hidden" name="query" value="{{ request('query') }}">

                    @include('components.catalog.filters')
                </form>
            </div>

            <div class="col-md-9">
                @if ($results->get('products')->isEmpty())
                    <div class="alert alert-warning">
                        {{ __("Geen resultaten gevonden voor ':query'", ['query' => request('query')]) }}
                    </div>
                @else
                    @include('components.catalog.products')
                @endif

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
