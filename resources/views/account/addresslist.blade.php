@extends('master', ['pagetitle' => 'Account / Adressenlijst'])

@section('title')
        <h3>Account <small>adressenlijst</small></h3>
@endsection

@section('content')
        <div class="modal fade" id="addAddressDialog" tabindex="-1" role="dialog" aria-labelledby="addAddress" aria-hidden="true">
                <form class="form-horizontal" action="/account/addAddress" method="POST" role="form">
                        {!! csrf_field() !!}
                        <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Adres toevoegen</h4>
                                        </div>
                                        <div class="modal-body">
                                                <div class="form-group">
                                                        <label for="inputName" class="col-sm-3 hidden-xs control-label">Naam*</label>
                                                        <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="name" class="form-control" id="inputName" placeholder="Naam" maxlength="100" required>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                        <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Straat + Huisnr*</label>
                                                        <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="street" class="form-control" id="inputStraat" placeholder="Straat + Huisnr" maxlength="50" required>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                        <label for="inputPostcode" class="col-sm-3 hidden-xs control-label">Postcode*</label>
                                                        <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="postcode" class="form-control" id="inputPostcode" placeholder="Postcode (XXXX YY)" maxlength="7" required>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                        <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Plaats*</label>
                                                        <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="city" class="form-control" id="inputPlaats" placeholder="Plaats" maxlength="30" required>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                        <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Telefoon</label>
                                                        <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="telephone" class="form-control" id="inputTelefoon" placeholder="Telefoon" maxlength="15">
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                        <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Mobiel</label>
                                                        <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="mobile" class="form-control" id="inputMobile" placeholder="Mobiel" maxlength="15">
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                                <div class="row">
                                                        <div class="col-sm-6">
                                                                <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Annuleren</button>
                                                        </div>

                                                        <br class="visible-xs" />

                                                        <div class="col-sm-6">
                                                                <button type="submit" class="btn btn-success btn-block">Toevoegen</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </form>
        </div>

        <div class="row">
                <div class="col-md-3">
                        @include('account.sidebar')
                </div>
                <div class="col-md-9">
                        <button data-target="#addAddressDialog" data-toggle="modal" class="btn btn-success btn-block">Adres toevoegen</button>
                        <table class="table table-striped">
                                <thead>
                                        <th>Naam</th>
                                        <th>Straat</th>
                                        <th>Postcode</th>
                                        <th>Plaats</th>
                                        <th>Telefoon</th>
                                        <th>Mobiel</th>
                                        <th>Verwijderen</th>
                                </thead>
                                <tbody>
                                        @foreach ($addresslist as $address)
                                                <tr>
                                                        <td>{{{ $address->name }}}</td>
                                                        <td>{{{ $address->street }}}</td>
                                                        <td>{{{ $address->postcode }}}</td>
                                                        <td>{{{ $address->city }}}</td>
                                                        <td>{{{ $address->telephone }}}</td>
                                                        <td>{{{ $address->mobile }}}</td>
                                                        <td>
                                                                <form action="/account/removeAddress" method="POST">
                                                                        {!! csrf_field() !!}
                                                                        <input class="hidden" value="{{{ $address->id }}}" name="id">
                                                                        <button type="submit" class="btn btn-danger">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                        </button>
                                                                </form>
                                                        </td>
                                                </tr>
                                        @endforeach
                                </tbody>
                        </table>
                </div>
        </div>
@endsection
