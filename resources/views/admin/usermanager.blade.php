@extends('master', ['pagetitle' => 'Admin / Gebruikers beheer'])

@section('title')
    <h3>Admin <small>gebruikers beheer</small></h3>
@endsection

@section('content')
    @include('admin.nav')

    <br />

    <h3>Debiteur toevoegen/aanpassen</h3>

    <hr />

    <form action="/admin/updateCompany" method="POST" class="form form-horizontal" id="addUserForm">

        <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Debiteur verwijderen</h4>
                    </div>
                    <div class="modal-body">
                        <p>U staat op het punt om debiteur <span id="companyID"></span> te verwijderen.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" name="delete">Verwijderen</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        {!! csrf_field() !!}

        <div class="form-group">
            <label for="company_id" class="col-sm-2 control-label">Debiteurnummer</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Debiteurnummer" type="number" name="company_id" id="inputCompanyId" value="{{ old('company_id') }}" required="" autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <label for="company_name" class="col-sm-2 control-label">Naam bedrijf</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Naam bedrijf" type="text" name="company_name" id="inputCompanyName" value="{{ old('company_name') }}" required="">
            </div>
        </div>

        <hr />

        <div class="form-group">
            <label for="address" class="col-sm-2 control-label">Adres</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Adres" type="text" name="address" id="inputAddress" value="{{ old('address') }}" required="">
            </div>
        </div>

        <div class="form-group">
            <label for="postcode" class="col-sm-2 control-label">Postcode</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Postcode" type="text" name="postcode" id="inputPostcode" value="{{ old('postcode') }}" required="">
            </div>
        </div>

        <div class="form-group">
            <label for="city" class="col-sm-2 control-label">Plaats</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Plaats" type="text" name="city" id="inputCity" value="{{ old('city') }}" required="">
            </div>
        </div>

        <hr />

        <div class="form-group">
            <label for="username" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Username" type="text" id="inputUsername" value="{{ old('company_id') }}" disabled="">
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="Email" type="email" name="email" id="inputEmail" value="{{ old('email') }}" required="">
            </div>
        </div>

        <div class="form-group">
            <label for="active" class="col-sm-2 control-label">Actief?</label>
            <div class="col-sm-2">
                <select name="active" class="form-control" id="inputActive">
                    <option value="0">Nee</option>
                    <option value="1">Ja</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <button id="deleteCompanyButton" type="button" class="btn btn-block btn-lg btn-danger" disabled="disabled" data-toggle="modal" data-target="#deleteUserModal">Verwijderen</button>
            </div>
            <div class="col-sm-10">
                <button type="submit" class="btn btn-block btn-lg btn-success" name="update">Toevoegen/wijzigen</button>
            </div>
        </div>
    </form>
@endsection

@section('extraJS')
    <script type="text/javascript">
        var $form = $('#addUserForm');
        var $companyId = $form.find('#inputCompanyId');
        var $companyName = $form.find('#inputCompanyName');
        var $address = $form.find('#inputAddress');
        var $postcode = $form.find('#inputPostcode');
        var $city = $form.find('#inputCity');
        var $username = $form.find('#inputUsername');
        var $email = $form.find('#inputEmail');
        var $active = $form.find('#inputActive');
        var $deleteButton = $form.find('#deleteCompanyButton');

        $companyId.keyup(function() {
            var value = $companyId.val();

            $username.val(value);
            $('#companyID').html(value);

            if (value == $companyId.val()) {
                $.ajax({
                    url: "/admin/api/company",
                    type: "GET",
                    dataType: "json",
                    data: {id: value},
                    success: function(data) {
                        var data = data.payload;

                        if (value == $companyId.val() && data != null) {
                            $companyName.val(data.company);
                            $address.val(data.street);
                            $postcode.val(data.postcode);
                            $city.val(data.city);
                            $email.val(data.main_user.email);
                            $active.val(data.active);

                            $deleteButton.removeAttr('disabled');
                        }
                    },
                    error: function() {
                        if (value == $companyId.val()) {
                            $companyName.val('');
                            $address.val('');
                            $postcode.val('');
                            $city.val('');
                            $email.val('');
                            $active.val(0);

                            $deleteButton.attr('disabled', 'disabled');
                        }
                    }
                });
            }
        });
    </script>
@endsection
