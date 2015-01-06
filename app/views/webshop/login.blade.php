@extends('master')

@section('title')
        <h3>Login</h3>
@stop

@section('content')
        <div class="row">
                <div class="col-md-offset-4 col-md-4">
                        <div class="well">
                                <form action="/login" method="POST" class="form">
                                        <div class="form-group">
                                                <label for="inputUsername" class="control-label">Gebruikersnaam</label>
                                                <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Gebruikersnaam" required @if(Session::has('username')) value="{{ Session::get('username') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                                <label for="inputPassword" class="control-label">Wachtwoord</label>
                                                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Wachtwoord" required>
                                        </div>
                                        <div class="form-group">
                                                <a href="/forgotpassword">Wachtwoord vergeten?</a>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </form>
                        </div>
                </div>
        </div>
@stop