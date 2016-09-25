@extends('master', ['pagetitle' => 'Login / Wachtwoord vergeten'])

@section('title')
    <h3>Wachtwoord vergeten?</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-offset-2 col-sm-8">
            <form method="POST" class="form-horizontal">
                {!! csrf_field() !!}

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="company_id">Debiteur nummer</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="Debiteur nummer" type="number" name="company_id" value="{{ old('company_id') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="username">Gebruikersnaam</label>
                    <div class="col-sm-8">
                        <input class="form-control" placeholder="Gebruikersnaam" type="text" name="username" value="{{ old('username') }}">
                    </div>
                </div>

                <br />

                <button class="btn btn-primary pull-right" type="submit">Aanvraag versturen</button>
            </form>
        </div>
    </div>
@endsection
