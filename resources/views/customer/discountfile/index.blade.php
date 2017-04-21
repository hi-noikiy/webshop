@extends('customer.master', ['pagetitle' => 'Account / Kortingsbestand'])

@section('title')
    <h3>Account <small>kortingsbestand</small></h3>
@endsection

@section('customer.content')
    <div class="text-center">
        <h3>ICC Bestand</h3>
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <a class="btn btn-default"
                   href="{{ route('customer.discountfile::generate', ['type' => 'icc', 'method' => 'download']) }}">
                    Downloaden
                </a>
            </div>
            <div class="btn-group">
                <a class="btn btn-default"
                   href="{{ route('customer.discountfile::generate', ['type' => 'icc', 'method' => 'mail']) }}">
                    Mailen
                </a>
            </div>
        </div>

        <hr />

        <h3>CSV Bestand</h3>
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <a class="btn btn-default"
                   href="{{ route('customer.discountfile::generate', ['type' => 'csv', 'method' => 'download']) }}">
                    Downloaden
                </a>
            </div>
            <div class="btn-group">
                <a class="btn btn-default"
                   href="{{ route('customer.discountfile::generate', ['type' => 'csv', 'method' => 'mail']) }}">
                    Mailen
                </a>
            </div>
        </div>
    </div>
@endsection
