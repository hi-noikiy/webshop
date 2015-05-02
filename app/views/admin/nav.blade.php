<ul class="nav nav-tabs" role="tablist">
        <li @if( Route::current()->getUri() === 'admin') class="active" @endif><a href="/admin">Overzicht</a></li>
        <li @if( Route::current()->getUri() === 'admin/import') class="active" @endif><a href="/admin/import">Importeren</a></li>
        <li @if( Route::current()->getUri() === 'admin/manageusers') class="active" @endif><a href="/admin/manageusers">Gebruikers beheren</a></li>
        <li @if( Route::current()->getUri() === 'admin/carousel') class="active" @endif><a href="/admin/carousel">Carousel</a></li>
        <li @if( Route::current()->getUri() === 'admin/pricelist') class="active" @endif><a href="/admin/pricelist">Prijslijst debiteur</a></li>
        <li @if( Route::current()->getUri() === 'admin/managecontent') class="active" @endif><a href="/admin/managecontent">Inhoud aanpassen</a></li>
        <li class="pull-right"><a href="/admin/logout">Uitloggen</a></li>
</ul>