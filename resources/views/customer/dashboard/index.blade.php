@extends('customer.master', ['pagetitle' => 'Account / Overzicht'])

@section('title')
    <h3>Account
        <small>overzicht</small>
    </h3>
@endsection

@section('customer.content')
    <table class="table">
        <tr>
            <td><b>Inlognaam</b></td>
            <td>{{ $customer->getUsername() }}</td>
        </tr>
        <tr>
            <td><b>Bedrijf</b></td>
            <td>{{ $company->getName() }}</td>
        </tr>
        <tr>
            <td><b>Correspondentie adres</b></td>
            <td>{{ $customer->getEmail() }}</td>
        </tr>
        <tr>
            <td><b>Aantal bestellingen</b></td>
            <td>{{ $orderCount }}</td>
        </tr>
    </table>
@endsection
