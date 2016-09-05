<div class="list-group">
    <a href="{{ url('account') }}" class="list-group-item @if( Route::current()->getUri() === 'account' ) active @endif">Overzicht</a>
    @if (Auth::user()->manager)
        <a href="{{ url('account/accounts') }}" class="list-group-item @if( Route::current()->getUri() === 'account/accounts' ) active @endif">Sub-accounts</a>
    @endif
    <a href="{{ route('change_password') }}" class="list-group-item {{ (Route::is('change_password') ? 'active' : '') }}">Wachtwoord wijzigen</a>
    <a href="{{ url('account/favorites') }}" class="list-group-item @if( Route::current()->getUri() === 'account/favorites' ) active @endif">Favorieten</a>
    <a href="{{ url('account/orderhistory') }}" class="list-group-item @if( Route::current()->getUri() === 'account/orderhistory' ) active @endif">Bestelgeschiedenis</a>
    <a href="{{ url('account/addresslist') }}" class="list-group-item @if( Route::current()->getUri() === 'account/addresslist' ) active @endif">Adressenlijst</a>
    <a href="{{ url('account/discountfile') }}" class="list-group-item @if( Route::current()->getUri() === 'account/discountfile' ) active @endif">Kortingsbestand genereren</a>
</div>
