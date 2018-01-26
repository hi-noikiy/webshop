@extends('layouts.admin')

@section('title', __('Klantbeheer'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-2">
                    <h3>
                        <i class="fal fa-fw fa-building"></i> Bedrijven
                    </h3>

                    <hr />

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Naam</th>
                            <th scope="col">Klantnummer</th>
                            <th scope="col">Aantal gebruikers</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                                <tr onclick="window.location.href='{{ route('admin.customer-manager', ['company' => $company->getId()]) }}'">
                                    <th scope="row">{{ $company->getId() }}</th>
                                    <td>{{ $company->getName() }}</td>
                                    <td>{{ $company->getCustomerNumber() }}</td>
                                    <td>{{ $company->getCustomers()->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr />

                    {{ $companies->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection