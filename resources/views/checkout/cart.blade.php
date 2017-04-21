@extends('layouts.main', ['pagetitle' => 'Webshop / Winkelwagen'])

@section('title')
    <h3>Winkelwagen</h3>
@endsection

@section('content')
    <div id="cart">
        @include('checkout.cart.table')
    </div>
@endsection