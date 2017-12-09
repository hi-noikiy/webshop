<div class="card">
    <div class="card-header">
        {{ __('Standaard afleveradres') }}
    </div>
    <div class="card-body">
        @if ($address)
            <address>
                <b>{{ $address->getAttribute('name') }}</b><br />
                {{ $address->getAttribute('street') }} <br />
                {{ $address->getAttribute('postcode') }} {{ $address->getAttribute('city') }} <br />
                <abbr title="{{ __('Telefoon') }}">T:</abbr> {{ $address->getAttribute('phone') }} <br />
                <abbr title="{{ __('Mobiel') }}">M:</abbr> {{ $address->getAttribute('mobile') }}
            </address>

            <a href="{{ routeIf('account.addresses') }}">
                {{ __('Standaard adres wijzigen') }}
            </a>
        @else
            <div class="alert alert-warning">
                {{ __("U hebt nog geen adressen aan uw account gekoppeld. Als er geen adres is gekoppeld, kunt u geen bestelling plaatsen.") }}
            </div>
        @endif
    </div>
</div>