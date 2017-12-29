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
                <i class="fal fa-fw fa-phone"></i> {{ $address->getAttribute('phone') }} <br />
                <i class="fal fa-fw fa-mobile"></i> {{ $address->getAttribute('mobile') }}
            </address>
        @else
            <div class="alert alert-warning">
                {{ __('U hebt nog geen standaard afleveradres geselecteerd.') }}
            </div>
        @endif

        <a href="{{ route('account.addresses') }}">
            {{ __('Standaard adres wijzigen') }}
        </a>
    </div>
</div>