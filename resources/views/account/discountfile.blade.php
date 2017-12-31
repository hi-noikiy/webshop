@extends('master', ['pagetitle' => 'Account / Kortingsbestand'])

@section('title')
        <h3>Account <small>kortingsbestand</small></h3>
@endsection

@section('content')
        <div class="row">
                <div class="col-md-3">
                        @include('account.sidebar')
                </div>
                <div class="col-md-9 text-center">
                        <h3>ICC Bestand</h3>
                        <div class="btn-group btn-group-justified">
                                <div class="btn-group">
                                        <a class="btn btn-default" href="/account/generate_icc/download">Downloaden</a>
                                </div>
                                <div class="btn-group">
                                        <a class="btn btn-default" href="/account/generate_icc/mail">Mailen</a>
                                </div>
                        </div>

                        <hr />

                        <h3>CSV Bestand</h3>
                        <div class="btn-group btn-group-justified">
                                <div class="btn-group">
                                        <a class="btn btn-default" href="/account/generate_csv/download">Downloaden</a>
                                </div>
                                <div class="btn-group">
                                        <a class="btn btn-default" href="/account/generate_csv/mail">Mailen</a>
                                </div>
                        </div>
                </div>
        </div>
@endsection
