<div class="list-group">
        <a href="/account/" class="list-group-item @if( Route::current()->getUri() === 'account' ) active @endif">Overzicht</a>
        <a href="/account/changepassword" class="list-group-item @if( Route::current()->getUri() === 'account/changepassword' ) active @endif">Wachtwoord wijzigen</a>
        <a href="/account/favorites" class="list-group-item @if( Route::current()->getUri() === 'account/favorites' ) active @endif">Favorieten</a>
        <a href="/account/orderhistory" class="list-group-item @if( Route::current()->getUri() === 'account/history' ) active @endif">Bestelgeschiedenis</a>
        <a href="/account/addresslist" class="list-group-item @if( Route::current()->getUri() === 'account/addresslist' ) active @endif">Adressenlijst</a>
        <a href="/account/discountfile" class="list-group-item @if( Route::current()->getUri() === 'account/discountfile' ) active @endif">Kortingsbestand genereren</a>
</div>
