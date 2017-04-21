<div class="list-group">
    <a href="{{ route('customer::account.dashboard') }}"
       class="list-group-item {{ Route::is('customer::account.dashboard') ? 'active' : '' }}">
        Overzicht
    </a>
    @if (Auth::user()->manager)
        <a href="{{ route('customer::account.accounts') }}"
           class="list-group-item {{ Route::is('customer::account.accounts') ? 'active' : '' }}">
            Sub-accounts
        </a>
    @endif
    <a href="{{ route('customer::account.password') }}"
       class="list-group-item {{ Route::is('customer::account.password') ? 'active' : '' }}">
        Wachtwoord wijzigen
    </a>
    <a href="{{ route('customer::account.favorites') }}"
       class="list-group-item {{ Route::is('customer::account.favorites') ? 'active' : '' }}">
        Favorieten
    </a>
    <a href="{{ route('customer::account.history') }}"
       class="list-group-item {{ Route::is('customer::account.history') ? 'active' : '' }}">
        Bestelgeschiedenis
    </a>
    <a href="{{ route('customer::account.addresses') }}"
       class="list-group-item {{ Route::is('customer::account.addresses') ? 'active' : '' }}">
        Adressenlijst
    </a>
    <a href="{{ route('customer::account.discountfile') }}"
       class="list-group-item {{ Route::is('customer::account.discountfile') ? 'active' : '' }}">
        Kortingsbestand genereren
    </a>
</div>
