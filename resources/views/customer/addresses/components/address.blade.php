<div class="col-md-4 col-sm-6 col-xs-12">
    <address>
        <strong>{{ $address->name }}</strong><br />
        {{ $address->street }}<br />
        {{ $address->city }}, {{ $address->postcode }}<br />
        {{ $address->telephone ? "T: " . $address->telephone : '' }}<br />
        {{ $address->mobile ? "M: " . $address->mobile : '' }}<br />
    </address>

    <form action="{{ route('customer.addresses::delete', ['id' => $address->id]) }}" method="POST">
        {{ csrf_field() }}

        <button type="submit" class="btn btn-danger btn-block">
            <i class="fa fa-fw fa-remove" aria-hidden="true"></i> Verwijderen
        </button>
    </form>

    <br />
</div>