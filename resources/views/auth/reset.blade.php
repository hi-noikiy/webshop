@extends('master')

@section('title')
        <h3>Wachtwoord vergeten?</h3>
@stop

@section('content')
        <div class="row">
                <div class="well col-md-offset-2 col-sm-8">

                        <form method="POST" action="/password/reset" class="form-horizontal">
                                {!! csrf_field() !!}
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group @if ($errors->has('email')) has-error @endif">
                                        <label class="col-sm-4 control-label" for="email">Gekoppelde email adres</label>
                                        <div class="col-sm-8">
                                                <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') }}">
                                        </div>
                                </div>

                                <div class="form-group @if ($errors->has('password')) has-error @endif">
                                        <label class="col-sm-4 control-label" for="password">Nieuw wachtwoord</label>
                                        <div class="col-sm-8">
                                                <input class="form-control" placeholder="Nieuw wachtwoord" type="password" name="password">
                                        </div>
                                </div>

                                <div class="form-group @if ($errors->has('password')) has-error @endif">
                                        <label class="col-sm-4 control-label" for="password_confirmation">Nieuw wachtwoord (verificatie)</label>
                                        <div class="col-sm-8">
                                                <input class="form-control" placeholder="Nieuw wachtwoord (verificatie)" type="password" name="password_confirmation">
                                        </div>
                                </div>

                                <br />

                                <button class="btn btn-primary btn-block" type="submit">Reset wachtwoord</button>
                        </form>
                </div>
        </div>
@stop
