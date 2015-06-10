<ul class="nav nav-tabs" role="tablist">
        <li @if( Route::current()->getUri() === 'admin') class="active" @endif><a href="/admin">Overzicht</a></li>
        <li @if( Route::current()->getUri() === 'admin/import') class="active" @endif><a href="/admin/import">Importeren</a></li>
        <li @if( Route::current()->getUri() === 'admin/manageusers') class="active" @endif><a href="/admin/manageusers">Gebruikers beheren</a></li>
        <li @if( Route::current()->getUri() === 'admin/carousel') class="active" @endif><a href="/admin/carousel">Carousel</a></li>
        <li @if( Route::current()->getUri() === 'admin/generate') class="active" @endif><a href="/admin/generate">Genereren</a></li>
        <li @if( Route::current()->getUri() === 'admin/managecontent') class="active" @endif><a href="/admin/managecontent">Inhoud aanpassen</a></li>
</ul>