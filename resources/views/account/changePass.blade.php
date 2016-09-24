@extends('master', ['pagetitle' => 'Account / Wachtwoord wijzigen'])

@section('title')
    <h3>Account <small>wachtwoord wijzigen</small></h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('account.sidebar')
        </div>
        <div class="col-md-9">
            <form action="{{ route('change_password') }}" method="POST" class="form-horizontal">

                {!! csrf_field() !!}

                <div class="form-group">
                    <label for="oldPass" class="col-sm-4 control-label">Huidig wachtwoord</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password_old" placeholder="Huidig wachtwoord" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="oldPassVerify" class="col-sm-4 control-label">Nieuw wachtwoord</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password" placeholder="Nieuw wachtwoord" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="newPass" class="col-sm-4 control-label">Nieuw wachtwoord (bevestiging)</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Nieuw wachtwoord (bevestiging)" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger pull-right">Wijzigen</button>

            </form>
        </div>
    </div>
@endsection
