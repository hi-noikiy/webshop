@extends('customer.master', ['pagetitle' => 'Account / Sub-Accounts'])

@section('title')
    <h3>Account <small>sub-accounts</small></h3>
@endsection

@section('before_content')
    <!-- Add user modal -->
    @include('customer.accounts.components.addModal')

    <!-- Edit user modal -->
    @include('customer.accounts.components.editModal')
@endsection

@section('customer.content')
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
                    <td>{{ $account->getUsername() }} <small>{{ $account->getIsMain() ? '[Hoofdaccount]' : '' }}</small></td>
                    <td>{{ $account->getEmail() }}</td>

                    @if ($account->isCurrent() && !$account->getIsMain())
                        <td>
                            <div class="fa fa-spinner fa-spin" style="display: none;"></div>
                            <input data-user="{{ $account }}" type="checkbox" name="manager"
                                   data-url="{{ route('customer.accounts::update', ['id' => $account->getId()]) }}"
                                   onchange="updateManager(this)" {{ $account->isManager() ? 'checked' : '' }}>
                        </td>
                        <td>
                            <button data-username="{{ $account->getUsername() }}"
                                    class="btn btn-danger deleteUserButton"><i class="glyphicon glyphicon-remove"></i>
                            </button>
                        </td>
                    @else
                        <td><input type="checkbox" disabled="disabled" checked></td>
                        <td>
                            {{ $account->isCurrent() ? 'Uw account' : '' }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('extraJS')
    @include('customer.accounts.components.javascript')
@endsection