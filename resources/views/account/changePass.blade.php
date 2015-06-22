@extends('master')

@section('title')
        <h3>Account <small>wachtwoord wijzigen</small></h3>
@stop

@section('content')
        <div class="row">
                <div class="col-md-3">
                        @include('account.sidebar')
                </div>
                <div class="col-md-9">
                        <div class="well">
                                <form action="/account/changepassword" method="POST" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                                <label for="oldPass" class="col-sm-4 control-label">Huidig wachtwoord</label>
                                                <div class="col-sm-8">
                                                        <input type="password" class="form-control" name="oldPass" placeholder="Huidig wachtwoord" required>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="oldPassVerify" class="col-sm-4 control-label">Nieuw wachtwoord</label>
                                                <div class="col-sm-8">
                                                        <input type="password" class="form-control" name="newPass" placeholder="Nieuw wachtwoord" required>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="newPass" class="col-sm-4 control-label">Nieuw wachtwoord (verificatie)</label>
                                                <div class="col-sm-8">
                                                        <input type="password" class="form-control" name="newPassVerify" placeholder="Nieuw wachtwoord (verificatie)" required>
                                                </div>
                                        </div>
                                        <button type="submit" class="btn btn-lg btn-block btn-danger">Wijzigen</button>
                                </form>
                        </div>
                </div>
        </div>
@stop