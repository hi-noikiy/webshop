<nav class="navbar navbar-expand-md navbar-dark {{ Route::is('home') ? 'bg-transparent' : 'bg-gradient' }}" id="navbar-second">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-links">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Route::is('home') ? 'active' :'' }}">
                    <a class="nav-link" href="{{ routeIf('home') }}">{{ trans('navigation.items.home') }}</a>
                </li>
                <li class="nav-item {{ Route::is('downloads') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ routeIf('downloads') }}">{{ trans('navigation.items.downloads') }}</a>
                </li>
                <li class="nav-item {{ Route::is('catalog.assortment') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ routeIf('catalog.assortment') }}">{{ trans('navigation.items.assortment') }}</a>
                </li>
            </ul>

            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    @if(auth()->check())
                        <li class="nav-item {{ request()->is('cart') ? 'active' : '' }}">
                            <mini-cart :count="{{ $cart->count() }}" cart-url="{{ route('checkout.cart') }}"></mini-cart>
                        </li>
                        <li class="nav-item dropdown {{ request()->is('account/*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                {{ trans('navigation.items.account') }}
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ routeIf('account.dashboard') }}">
                                    <span class="glyphicon glyphicon-user"></span> {{ trans('navigation.items.dashboard') }}
                                </a>
                                <a class="dropdown-item" href="{{ routeIf('account.favorites') }}">
                                    <span class="glyphicon glyphicon-heart"></span> {{ trans('navigation.items.favorites') }}
                                </a>
                                <a class="dropdown-item" href="{{ routeIf('account.order-history') }}">
                                    <span class="glyphicon glyphicon-time"></span> {{ trans('navigation.items.order_history') }}
                                </a>
                                <a class="dropdown-item" href="{{ routeIf('account.discountfile') }}">
                                    <span class="glyphicon glyphicon-euro"></span> {{ trans('navigation.items.discount_file') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit()">
                                    <span class="glyphicon glyphicon-off"></span> {{ trans('navigation.items.logout') }}
                                </a>
                            </div>
                        </li>

                        <form class="hidden" action="{{ routeIf('auth.logout') }}" method="post" id="logout-form">
                            {{ csrf_field() }}
                        </form>
                    @else
                        <li class="nav-item">
                            <a class="nav-link register-button" href="{{ routeIf('auth.register.form') }}">{{ trans('navigation.items.register') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ routeIf('auth.login', ['toUrl' => url()->current()]) }}">{{ trans('navigation.items.login') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>

{{--<nav class="navbar navbar-default navbar-static-top" id="navbar-second">--}}
    {{--<div class="container">--}}
        {{--<div class="collapse navbar-collapse" id="navbar-links">--}}
            {{--<ul class="nav navbar-nav">--}}
                {{--<li class="nav-item {{ Route::is('home') ? 'active' :'' }}">--}}
                    {{--<a href="{{ routeIf('home') }}">{{ trans('navigation.items.home') }}</a>--}}
                {{--</li>--}}
                {{--<li class="{{ Route::is('downloads') ? 'active' : '' }}">--}}
                    {{--<a href="{{ routeIf('downloads') }}">{{ trans('navigation.items.downloads') }}</a>--}}
                {{--</li>--}}
                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ trans('navigation.items.webshop') }} <span class="caret"></span></a>--}}
                    {{--<ul class="dropdown-menu" role="menu">--}}
                        {{--<li><a href="{{ routeIf('catalog::assortment.index') }}">{{ trans('navigation.items.assortment') }}</a></li>--}}
                        {{--<li><a href="{{ routeIf('search') }}">{{ trans('navigation.items.search') }}</a></li>--}}
                        {{--<li><a href="{{ routeIf('catalog::assortment.deals') }}">{{ trans('navigation.items.deals') }}</a></li>--}}
                        {{--<li><a href="{{ routeIf('sales') }}">{{ trans('navigation.items.sales') }}</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--@if(auth()->user() && auth()->user()->hasRole(\WTG\Models\Customer::CUSTOMER_ROLE_SUPER_ADMIN))--}}
                    {{--<li><a href="{{ routeIf('admin::dashboard') }}">{{ trans('navigation.items.admin') }}</a></li>--}}
                {{--@endif--}}
            {{--</ul>--}}

            {{--<div class="navbar-right">--}}
                {{--<ul class="nav navbar-nav">--}}
                    {{--@if(auth()->check())--}}
                        {{--<li class="{{ request()->is('cart') ? 'active' : '' }}">--}}
                            {{--<a href="{{ routeIf('checkout::cart.index') }}" style="height: 50px">--}}
                                {{--{{ trans('navigation.items.cart') }} <span class="badge" id="cart-badge">{{ $quote->getItemCount() }}</span>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="dropdown {{ request()->is('account/*') ? 'active' : '' }}">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('navigation.items.account') }} <span class="caret"></span></a>--}}
                            {{--<ul class="dropdown-menu" role="menu">--}}
                                {{--<li><a href="{{ routeIf('account.dashboard') }}"><span class="glyphicon glyphicon-user"></span> {{ trans('navigation.items.dashboard') }}</a></li>--}}
                                {{--<li><a href="{{ routeIf('account.favorites') }}"><span class="glyphicon glyphicon-heart"></span> {{ trans('navigation.items.favorites') }}</a></li>--}}
                                {{--<li><a href="{{ routeIf('account.history') }}"><span class="glyphicon glyphicon-time"></span> {{ trans('navigation.items.order_history') }}</a></li>--}}
                                {{--<li><a href="{{ routeIf('account.discountfile') }}"><span class="glyphicon glyphicon-euro"></span> {{ trans('navigation.items.discount_file') }}</a></li>--}}
                                {{--<li class="divider"></li>--}}
                                {{--<li><a href="#" onclick="document.getElementById('logout-form').submit()"><span class="glyphicon glyphicon-off"></span> {{ trans('navigation.items.logout') }}</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}

                        {{--<form class="hidden" action="{{ routeIf('auth.logout') }}" method="post" id="logout-form">--}}
                            {{--{{ csrf_field() }}--}}
                        {{--</form>--}}
                    {{--@else--}}
                        {{--<li><a class="register-button" href="{{ routeIf('auth.register.form') }}">{{ trans('navigation.items.register') }}</a></li>--}}
                        {{--<li><a href="{{ routeIf('auth.login', ['toUrl' => url()->current()]) }}">{{ trans('navigation.items.login') }}</a></li>--}}
                    {{--@endif--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</nav>--}}