@extends('layouts.main', ['pagetitle' => 'Webshop / Zoeken'])

@section('title')
    <h3>{{ $title }}</h3>
@endsection

@section('content')
    <div class="alert alert-success" role="alert">
        {{ $results->total() }} resultaten gevonden in {{ $scriptTime }} seconden.
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th></th>
            <th class="hidden-xs">Artikelnummer</th>
            <th>Omschrijving</th>
            @if(Auth::check())
                <th>Netto prijs</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($results as $product)
            <tr class="success">
                <td class="product-thumbnail"><img src="/img/products/{{ $product->image }}" alt="{{ $product->image }}"></td>
                <td class="hidden-xs">{{ $product->number }}</td>
                <td><a href="/product/{{ $product->number }}">{{ $product->name }}</a></td>
                @if(Auth::check())
                    <td>&euro;{{ $product->real_price }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $results->appends(array('brand' => request('brand'), 'serie' => request('serie'), 'type' => request('type')))->render() !!}
    </div>
@endsection
