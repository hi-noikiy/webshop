@extends('layouts.admin')

@section('title', __('Klantbeheer - :company', ['company' => $company->getName()]))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-2">
                    <h3>
                        <i class="fal fa-fw fa-user"></i> Accounts
                    </h3>

                    <hr />

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Gebruikersnaam</th>
                            <th scope="col">Actief</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->getUsername() }}</td>
                                    <td>{{ $customer->getActive() ? 'Ja' : 'Nee' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr />

                    {{ $customers->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection