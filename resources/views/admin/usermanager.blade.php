@extends('master')

@section('title')
        <h3>Admin <small>user manager</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <h3>Gebruikers toevoegen/wijzigen</h3>

        <br />

        <form action="/admin/updateUser" method="POST" class="form form-horizontal">

                <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Gebruiker verwijderen</h4>
                                        </div>
                                        <div class="modal-body">
                                                <p>U staat op het punt om gebruiker <span id="userID"></span> te verwijderen.</p>
                                        </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger" name="delete">Verwijderen</button>
                                        </div>
                                </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                        <label for="login" class="col-sm-2 control-label">Debiteurnummer</label>
                        <div class="col-sm-10">
                                <input class="form-control" placeholder="Inlognaam" type="text" name="login" id="inputUserId" required="" autocomplete="off">
                        </div>
                </div>
                <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Naam bedrijf</label>
                        <div class="col-sm-10">
                                <input class="form-control" placeholder="Naam bedrijf" type="text" name="name" id="inputUserName" required="">
                        </div>
                </div>
                <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">E-Mail</label>
                        <div class="col-sm-10">
                                <input class="form-control" placeholder="E-Mail" type="text" name="email" id="inputUserEmail" required="">
                        </div>
                </div>
                <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Straat</label>
                        <div class="col-sm-10">
                                <input class="form-control" placeholder="Straat" type="text" name="street" id="inputUserStreet" required="">
                        </div>
                </div>
                <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Postcode</label>
                        <div class="col-sm-10">
                                <input class="form-control" placeholder="Postcode" type="text" name="postcode" id="inputUserPostcode" required="">
                        </div>
                </div>
                <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Plaats</label>
                        <div class="col-sm-10">
                                <input class="form-control" placeholder="Plaats" type="text" name="city" id="inputUserCity" required="">
                        </div>
                </div>
                <div class="form-group">
                        <label for="active" class="col-sm-2 control-label">Actief?</label>
                        <div class="col-sm-2">
                                <select name="active" class="form-control" id="inputUserActive">
                                        <option value="0">Nee</option>
                                        <option value="1">Ja</option>
                                </select>
                        </div>
                </div>
                <div class="form-group">
                        <div class="col-sm-2">
                                <button class="btn btn-block btn-lg btn-danger" data-toggle="modal" data-target="#deleteUserModal">Verwijderen</button>
                        </div>
                        <div class="col-sm-10">
                                <button type="submit" class="btn btn-block btn-lg btn-success" name="update">Toevoegen/wijzigen</button>
                        </div>
                </div>
        </form>
@stop

@section('extraJS')
        <script type="text/javascript">
                $('#inputUserId').keyup(function() {
                var value = $(this).val();
                $('#userID').html(value);

                if (value = $(this).val())
                {
                        $.ajax({
                                url: "/admin/getUserData",
                                type: "GET",
                                dataType: "json",
                                data: {id: value},
                                success: function(data) {
                                        if (value == $('#inputUserId').val() && data != null) {
                                                $('#inputUserName').attr('value', data['company']);
                                                $('#inputUserEmail').attr('value', data['email']);
                                                $('#inputUserStreet').attr('value', data['street']);
                                                $('#inputUserPostcode').attr('value', data['postcode']);
                                                $('#inputUserCity').attr('value', data['city']);
                                                $('#inputUserActive').val(data['active']);
                                        }
                                },
                                error: function(data) {
                                        $('#inputUserName').attr('value', '');
                                        $('#inputUserEmail').attr('value', '');
                                        $('#inputUserStreet').attr('value', '');
                                        $('#inputUserPostcode').attr('value', '');
                                        $('#inputUserCity').attr('value', '');
                                        $('#inputUserActive').val(0);
                                }
                        });
                }
        });
        </script>
@stop