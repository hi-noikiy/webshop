@extends('master', ['pagetitle' => 'Login / Wachtwoord vergeten'])

@section('title')
    <h3>Wachtwoord vergeten?</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-offset-2 col-sm-8">

            <form method="POST" action="/password/reset" class="form-horizontal">
                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group @if ($errors->has('company_id')) has-error @endif">
                    <label class="col-sm-4 control-label" for="company_id">Debiteur nummer</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="Debiteur nummer" type="number" name="company_id" value="{{ old('company_id') }}">
                    </div>
                </div>

                <div class="form-group @if ($errors->has('username')) has-error @endif">
                    <label class="col-sm-4 control-label" for="username">Gebruikersnaam</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="Gebruikersnaam" type="text" name="username" value="{{ old('username') }}">
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
@endsection
