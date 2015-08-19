@extends('master')

@section('title')
        <h3>Admin <small>user manager</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <h3>1. Selecteer een actie</h3>

        <hr />

        <div class="row">
                <div class="col-md-12">
                        <select class="form-control">
                                <option value="false">Selecteer een actie</option>
                                <option value="addUser">Gebruiker toevoegen</option>
                                <option value="modUser">Gebruiker wijzigen</option>
                                <option value="importUsers">Gebruikers bestand inlezen</option>
                        </select>
                </div>
        </div>

<!--        <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                        <div class="panel-heading">
                                <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed">
                                                Gebruiker toevoegen/wijzigen
                                        </a>
                                </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">
                                        <form action="/admin/addEditUser" method="POST" class="form form-horizontal">
                                                <div class="form-group">
                                                        <label for="login" class="col-sm-2 control-label">Debiteurnummer</label>
                                                        <div class="col-sm-10">
                                                                <input class="form-control" placeholder="Inlognaam" type="text" name="login" id="inputUserId" required="">
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
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                                <button type="submit" class="btn btn-block btn-lg btn-success">Toevoegen/wijzigen</button>
                                                        </div>
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>
                <div class="panel panel-default">
                        <div class="panel-heading">
                                <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed">
                                                Gebruikers bestand uploaden
                                        </a>
                                </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                        <div class="alert alert-warning" role="alert"><strong>Waarschuwing!</strong> Dit zal ALLE gebruikers instellingen overschrijven. Favorieten en door de gebruikers aangepaste wachtwoorden zullen verloren gaan. Dit kan NIET ongedaan worden gemaakt!</div>
                                        <form action="/admin/uploadUsers" method="POST" enctype="multipart/form-data" class="form">
                                                <div class="form-group">
                                                        <label for="userfile">Gebruikers bestand</label>
                                                        <input type="file" name="userfile">
                                                        <p class="help-block">.csv of .txt, max. 128M</p>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Uploaden</button>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>-->
@stop