@extends('customer.master', ['pagetitle' => 'Account / Adressenlijst'])

@section('title')
    <h3>Account <small>adressenlijst</small></h3>
@endsection

@section('before_content')
    @include('customer.addresses.components.modal')
@endsection

@section('customer.content')
    <button data-target="#addAddressDialog" data-toggle="modal" class="btn btn-success btn-block">
        <i class="fa fa-fw fa-plus" aria-hidden="true"></i> Adres toevoegen
    </button>

    <hr />

    <div class="row">
        @foreach ($addresses as $address)
            @include('customer.addresses.components.address')
        @endforeach
    </div>
@endsection
