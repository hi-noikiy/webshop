@extends('master', ['pagetitle' => 'Account / Sub-Accounts'])

@section('title')
    <h3>Account <small>sub-accounts</small></h3>
@endsection

@section('content')

    <!-- Add user modal -->
    <div class="modal fade" id="addAccountDialog" tabindex="-1" role="dialog" aria-labelledby="addAccount" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" method="POST" role="form">

                    {!! csrf_field() !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Sub account toevoegen</h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-3 hidden-xs control-label">Gebruikersnaam*</label>
                            <div class="col-xs-12 col-sm-9">
                                <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Gebruikersnaam" maxlength="100" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail" class="col-sm-3 hidden-xs control-label">Email*</label>
                            <div class="col-xs-12 col-sm-9">
                                <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" maxlength="150" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword" class="col-sm-3 hidden-xs control-label">Wachtwoord*</label>
                            <div class="col-xs-12 col-sm-9">
                                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Wachtwoord" maxlength="100" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPasswordVerify" class="col-sm-3 hidden-xs control-label">Wachtwoord (verificatie)*</label>
                            <div class="col-xs-12 col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control" id="inputPasswordVerify" placeholder="Wachtwoord (verificatie)" maxlength="100" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <label>
                                        <input type="checkbox" name="manager" id="inputManager" value="1"> Maak dit account manager
                                    </label>

                                    <p class="help-block">Managers kunnen sub accounts toevoegen en verwijderen.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Annuleren</button>
                            </div>

                            <br class="visible-xs" />

                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success btn-block">Toevoegen</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit user modal -->
    <div class="modal fade" id="deleteAccountDialog" tabindex="-2" role="dialog" aria-labelledby="deleteAccount" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" action="{{ route('delete_subaccount') }}" method="POST" role="form">

                    {!! csrf_field() !!}

                    <input type="hidden" value="" id="deleteUsernameInput" name="username">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Sub account verwijderen</h4>
                    </div>

                    <div class="modal-body">

                        <div class="alert alert-warning">
                            <h4>
                                Waarschuwing! U staat op het punt om het sub-account met gebruikersnaam '<span id="deleteUsername"></span>' te verwijderen.<br />
                                Dit zal niet ongedaan gemaakt kunnen worden, de favorieten en bestelgeschiedenis zullen verloren gaan!<br />
                                Weet u zeker dat u deze gebruiker wilt verwijderen?
                            </h4>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="delete" id="inputManager" value="1" required> Verwijder gebruiker en bijbehorende gegevens
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Annuleren</button>
                            </div>

                            <br class="visible-xs" />

                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-danger btn-block">Verwijderen</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            @include('account.sidebar')
        </div>
        <div class="col-md-9">
            <button data-target="#addAccountDialog" data-toggle="modal" class="btn btn-success btn-block btn-lg">Sub account toevoegen</button>

            <hr />

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Gebruikersnaam</th>
                    <th>Email</th>
                    <th>Manager</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->username }} <small>{{ $account->isMain() ? '[Hoofdaccount]' : '' }}</small></td>
                        <td>{{ $account->email }}</td>

                        @if ($account->id !== Auth::id() && !$account->isMain())
                            <td>
                                <div class="fa fa-spinner fa-spin" style="display: none;"></div>
                                <input data-user="{{ $account }}" data-url="{{ route('update_subaccount', ['id' => $account->id]) }}" type="checkbox" name="manager" onchange="updateManager(this)" {{ $account->manager ? 'checked' : '' }}>
                            </td>
                            <td><button data-username="{{ $account->username }}" class="btn btn-danger deleteUserButton"><i class="glyphicon glyphicon-remove"></i></button></td>
                        @else
                            <td><input type="checkbox" disabled="disabled" checked></td>
                            <td>
                                {{ $account->id === Auth::id() ? 'Uw account' : '' }}
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $accounts->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('extraJS')
    <script>
        $('.deleteUserButton').click(function() {
            var username = $(this).attr('data-username');

            $('#deleteUsername').html(username);
            $('#deleteUsernameInput').attr('value', username);

            $('#deleteAccountDialog').modal('toggle');
        });

        function updateManager(target) {
            var user = $.parseJSON($(target).attr('data-user'));
            var spinner = $(target).siblings('.fa-spinner');

            $(target).hide();
            $(spinner).show();

            $.ajax({
                url: $(target).attr('data-url'),
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                dataType: 'json',
                success: function (data) {
                    $(target).prop('checked', target.checked);
                },
                error: function (data) {
                    $(target).prop('checked', target.checked != true);
                },
                complete: function () {
                    $(spinner).hide();
                    $(target).show();
                }
            });
        }
    </script>
@endsection