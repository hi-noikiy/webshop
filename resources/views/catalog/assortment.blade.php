@extends('layouts.main', ['pagetitle' => 'Assortiment'])

@section('title')
    <h3>Assortiment</h3>
@endsection

@section('content')
    @if ($products->count() === 0)
        <div class="alert alert-warning">Geen producten gevonden in het assortiment.</div>
    @else
        @include('catalog.assortment.filters')

        @include('catalog.assortment.table')
    @endif
@endsection