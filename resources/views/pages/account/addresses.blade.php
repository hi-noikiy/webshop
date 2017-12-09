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

            <div class="alert alert-warning mt-3">
                <b>{{ __("Let op:") }}</b> {{ __("Als het standaard adres veranderd wordt, veranderd dit voor alle accounts die gekoppeld zijn aan het debiteurnummer.") }}
            </div>
        </div>
    </div>

    <div class="row">
        @forelse ($addresses as $address)
            <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <address>
                            <b>{{ $address->getAttribute('name') }}</b><br />
                            {{ $address->getAttribute('street') }} <br />
                            {{ $address->getAttribute('postcode') }} {{ $address->getAttribute('city') }} <br />
                            <abbr title="{{ __('Telefoon') }}">T:</abbr> {{ $address->getAttribute('phone') }} <br />
                            <abbr title="{{ __('Mobiel') }}">M:</abbr> {{ $address->getAttribute('mobile') }}
                        </address>

                        @if ($address->getAttribute('id') !== $customer->getDefaultAddress()->getAttribute('id'))
                            <form method="POST">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}
                                <input name="address-id" value="{{ $address->getAttribute('id') }}" type="hidden" />
                                <button type="submit" class="btn btn-secondary">{{ __("Maak standaard adres") }}</button>
                            </form>
                        @else
                            <button type="submit" class="btn btn-disabled" disabled>{{ __("Standaard adres") }}</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning mx-auto">
                {{ __("U hebt nog geen addressen gekoppeld aan uw account.") }}
            </div>
        @endforelse
    </div>
@endsection