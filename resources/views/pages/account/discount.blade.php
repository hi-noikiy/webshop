@extends('layouts.account')

@section('title', __('Account / Kortingsbestand'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ trans('titles.account.discount') }}
    </h2>
@endsection

@section('account.content')
    <div class="row">
        <div class="col-12">
            <p>{{ __("Hier kunt u uw kortingsbestand ophalen in CSV of in ICC formaat. Dit bestand kan gedownload worden of naar uw contact e-mail adres (:email) gestuurd worden.", ['email' => $customer->contact->getAttribute('contact_email')]) }}</p>

            <form method="post">
                {{ csrf_field() }}

                <div class="row mb-3">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-control-label">
                                <b>{{ __("Bestandstype") }}</b>
                            </label>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="format"
                                           value="{{ \WTG\Services\DiscountFileService::FORMAT_TYPE_ICC }}" checked>
                                    {{ __("ICC") }}
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="format"
                                           value="{{ \WTG\Services\DiscountFileService::FORMAT_TYPE_CSV }}">
                                    {{ __("CSV") }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-3">
                            <label class="form-control-label">
                                <b>{{ __("Ontvangstwijze") }}</b>
                            </label>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="receive" value="download" checked>
                                    {{ __("Download") }}
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="receive" value="email">
                                    {{ __("E-Mail") }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-outline-primary" type="submit">{{ __("Verzenden") }}</button>
            </form>
        </div>
    </div>

    {{--<div class="row">--}}
        {{--<div class="col-12 col-sm-6">--}}
            {{--<div class="card">--}}
                {{--<div class="card-header">--}}
                    {{--{{ __("CSV Bestand") }}--}}
                {{--</div>--}}

                {{--<div class="card-body">--}}
                    {{--<form method="post" action="{{ route('account.discount.csv') }}">--}}
                        {{--{{ csrf_field() }}--}}

                        {{--<div class="form-check">--}}
                            {{--<label class="form-check-label">--}}
                                {{--<input class="form-check-input" type="radio" name="type" value="download" checked>--}}
                                {{--{{ __("Downloaden") }}--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="form-check">--}}
                            {{--<label class="form-check-label">--}}
                                {{--<input class="form-check-input" type="radio" name="type" value="email">--}}
                                {{--{{ __("E-Mail") }}--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="col-12 col-sm-6">--}}
            {{--<div class="card">--}}
                {{--<div class="card-header">--}}
                    {{--{{ __("ICC Bestand") }}--}}
                {{--</div>--}}

                {{--<div class="card-body">--}}
                    {{--<form method="post" action="{{ route('account.discount.icc') }}">--}}
                        {{--{{ csrf_field() }}--}}

                        {{--<div class="form-check">--}}
                            {{--<label class="form-check-label">--}}
                                {{--<input class="form-check-input" type="radio" name="type" value="download" checked>--}}
                                {{--{{ __("Downloaden") }}--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="form-check">--}}
                            {{--<label class="form-check-label">--}}
                                {{--<input class="form-check-input" type="radio" name="type" value="email">--}}
                                {{--{{ __("E-Mail") }}--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection