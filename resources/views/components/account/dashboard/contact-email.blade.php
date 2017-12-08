<div class="card">
    <div class="card-header">
        {{ __("Contact e-mail") }}
    </div>
    <div class="card-body">
        <p class="card-text">
            {{ __("Naar dit e-mail adres worden contact gerelateerde mails gestuurd, bijvoorbeeld een wachtwoord reset link.") }}
        </p>

        <form method="POST">
            {{ csrf_field() }}
            <input class="form-control" title="Order email" name="order_email" type="email"
                   value="{{ $customer->contact->getAttribute('contact_email') }}"
                   oninput="toggleSaveButton()" data-required="true"
                   data-initial="{{ $customer->contact->getAttribute('contact_email') }}" />

            <button type="submit" class="btn btn-success my-2" style="display: none;">
                {{ __('Opslaan') }}
            </button>
        </form>
    </div>
</div>