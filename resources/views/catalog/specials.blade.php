@extends('layouts.main', ['pagetitle' => 'Actieproducten'])

@section('title')
    <h3>Actieproducten</h3>
@endsection

@section('content')
    @if ($products->count() === 0)
        <div class="alert alert-warning">Geen actieproducten gevonden in het assortiment.</div>
    @else
        @include('catalog.assortment.table')
    @endif
@endsection