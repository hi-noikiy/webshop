@extends('master', ['pagetitle' => 'Login / Wachtwoord vergeten'])

@section('title')
        <h3>Wachtwoord vergeten?</h3>
@stop

@section('content')
        <div class="row">
                <div class="well col-md-offset-2 col-sm-8">
                        <form method="POST" action="/password/email" class="form-horizontal">
                                {!! csrf_field() !!}

                                <div class="form-group">
                                        <label class="col-sm-4 control-label" for="email">Gekoppelde email adres</label>
                                        <div class="col-sm-8">
                                                <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') }}">
                                        </div>
                                </div>

                                <br />

                                <button class="btn btn-primary btn-block" type="submit">Aanvraag versturen</button>
                        </form>
                </div>
        </div>
@stop
