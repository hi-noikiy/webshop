@extends('master', ['pagetitle' => 'Account / Sub-Accounts'])

@section('title')
    <h3>Account <small>sub-accounts</small></h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('account.sidebar')
        </div>
        <div class="col-md-9">
            <button data-target="#addAccountDialog" data-toggle="modal" class="btn btn-success btn-block btn-lg">Account toevoegen</button>

            <hr />

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Login</th>
                    <th>Manager?</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->username }}</td>
                        <td>{{ $account->manager ? 'ja' : 'nee' }}</td>
                        <td><a href="#" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>
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
