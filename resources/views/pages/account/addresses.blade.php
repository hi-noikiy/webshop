@extends('layouts.account')

@section('title', __('Account / Adressen'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ trans('titles.account.addresses') }}
    </h2>
@endsection

@section('account.content')
    @include('components.account.addresses.addModal')

    <div class="row">
        <div class="col-12">
            <button class="btn btn-success" data-toggle="modal" data-target="#address-modal">
                {{ __("Adres toevoegen") }}
            </button>

            {{--<div class="alert alert-warning mt-3">--}}
                {{--<b>{{ __("Let op:") }}</b> {{ __("Als het standaard adres veranderd wordt, veranderd dit voor alle accounts die gekoppeld zijn aan het debiteurnummer.") }}--}}
            {{--</div>--}}
        </div>
    </div>

    <address-list :addresses="{{ $addresses }}" update-url="{{ route('account.addresses') }}"
                  default-address="{{ $defaultAddress }}"></address-list>
@endsection