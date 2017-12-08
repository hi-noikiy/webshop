<div class="modal fade" id="change-address-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Selecteer een adres") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @forelse ($addresses as $address)
                        <div class="col-xs-12 col-sm-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <address id="address-{{ $address->getAttribute('id') }}">
                                        <b>{{ $address->getAttribute('name') }}</b><br />
                                        {{ $address->getAttribute('street') }} <br />
                                        {{ $address->getAttribute('postcode') }} {{ $address->getAttribute('city') }}
                                    </address>

                                    <button class="btn btn-outline-primary btn-sm change-address-button"
                                            data-target="#address-{{ $address->getAttribute('id') }}"
                                            data-address-id="{{ $address->getAttribute('id') }}">
                                        {{ __("Selecteer adres") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            {{ __("U hebt nog geen addressen gekoppeld aan uw account.") }}
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Sluiten") }}</button>
                <button type="button" class="btn btn-primary">{{ __("Selecteren") }}</button>
            </div>
        </div>
    </div>
</div>