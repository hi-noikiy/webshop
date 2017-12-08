<div class="list-group pb-3">
    <a href="{{ routeIf('account.dashboard') }}"
       class="list-group-item {{ Route::is('account.dashboard') ? 'active' : '' }}">
        {{ __('Dashboard') }}
    </a>
    @if (auth()->user()->can('view accounts'))
        <a href="{{ routeIf('account.sub_accounts') }}"
           class="list-group-item {{ Route::is('account.sub_accounts') ? 'active' : '' }}">
            {{ __('Sub-accounts') }}
        </a>
    @endif
    <a href="{{ routeIf('account.change_password') }}"
       class="list-group-item {{ Route::is('account.change_password') ? 'active' : '' }}">
        {{ __('Wachtwoord wijzigen') }}
    </a>
    <a href="{{ routeIf('account.favorites') }}"
       class="list-group-item {{ Route::is('account.favorites') ? 'active' : '' }}">
        {{ __('Favorieten') }}
    </a>
    <a href="{{ routeIf('account.order-history') }}"
       class="list-group-item {{ Route::is('account.order-history') ? 'active' : '' }}">
        {{ __('Bestelhistorie') }}
    </a>
    <a href="{{ routeIf('account.addresses') }}"
       class="list-group-item {{ Route::is('account.addresses') ? 'active' : '' }}">
        {{ __('Adressen') }}
    </a>
    <a href="{{ routeIf('account.discount') }}"
       class="list-group-item {{ Route::is('account.discount') ? 'active' : '' }}">
        {{ __('Kortingsbestand genereren') }}
    </a>
</div>
