<!-- Desktop navigation -->
<ul class="nav nav-tabs hidden-xs hidden-sm" role="tablist">
    <li @if( Route::current()->getUri() === 'admin') class="active" @endif><a href="{{ url('admin') }}">Overzicht</a></li>
    <li @if( Route::current()->getUri() === 'admin/import') class="active" @endif><a href="{{ url('admin/import') }}">Importeren</a></li>
    <li @if( Route::current()->getUri() === 'admin/usermanager') class="active" @endif><a href="{{ url('admin/usermanager') }}">Gebruikers beheren</a></li>
    <li @if( Route::current()->getUri() === 'admin/carousel') class="active" @endif><a href="{{ url('admin/carousel') }}">Carousel</a></li>
    <li @if( Route::current()->getUri() === 'admin/generate') class="active" @endif><a href="{{ url('admin/generate') }}">Genereren</a></li>
    <li @if( Route::current()->getUri() === 'admin/managecontent') class="active" @endif><a href="{{ url('admin/managercontent') }}">Inhoud aanpassen</a></li>
    <li @if( Route::current()->getUri() === 'admin/packs') class="active" @endif><a href="{{ url('admin/packs') }}">Actiepaketten</a></li>
</ul>

<!-- Mobile navigation -->
<div class="list-group hidden-md hidden-lg">
    <a class="list-group-item @if( Route::current()->getUri() === 'admin') active @endif " href="{{ url('admin') }}">Overzicht</a>
    <a class="list-group-item @if( Route::current()->getUri() === 'admin/import') active @endif " href="{{ url('admin/import') }}">Importeren</a>
    <a class="list-group-item @if( Route::current()->getUri() === 'admin/usermanager') active @endif " href="{{ url('admin/usermanager') }}">Gebruikers beheren</a>
    <a class="list-group-item @if( Route::current()->getUri() === 'admin/carousel') active @endif " href="{{ url('admin/carousel') }}">Carousel</a>
    <a class="list-group-item @if( Route::current()->getUri() === 'admin/generate') active @endif " href="{{ url('admin/generate') }}">Genereren</a>
    <a class="list-group-item @if( Route::current()->getUri() === 'admin/managecontent') active @endif " href="{{ url('admin/managercontent') }}">Inhoud aanpassen</a>
    <a class="list-group-item @if( Route::current()->getUri() === 'admin/packs') active @endif " href="{{ url('admin/packs') }}">Actiepaketten</a></a>
</div>
