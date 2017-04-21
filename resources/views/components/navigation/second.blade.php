<nav class="navbar navbar-default navbar-static-top" id="navbar-second">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-links">
            <ul class="nav navbar-nav">
                <li class="{{ Route::is('home') ? 'active' :'' }}"><a href="{{ route('home') }}">Home</a></li>
                <li class="dropdown {{ request()->is('about') || request()->is('contact') || request()->is('assortment') ? 'active' : '' }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Info <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('about') }}">Het bedrijf</a></li>
                        <li><a href="{{ url('contact') }}">Contact</a></li>
                        <li><a href="{{ url('assortment') }}">Assortiment</a></li>
                    </ul>
                </li>
                <li class="{{ request()->is('downloads') ? 'active' : '' }}"><a href="{{ url('downloads') }}">Downloads</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Webshop <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('catalog::assortment.index') }}">Assortiment</a></li>
                        <li><a href="{{ url('search') }}">Zoeken</a></li>
                        <li><a href="{{ route('catalog::assortment.specials') }}">Acties</a></li>
                        <li><a href="{{ url('clearance') }}">Opruiming</a></li>
                    </ul>
                </li>
                @if(Auth::check() && Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                @endif
            </ul>

            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    @if(Auth::check())
                        <li class="{{ request()->is('cart') ? 'active' : '' }}">
                            <a href="{{ route('checkout::cart.index') }}" style="height: 50px">
                                Winkelwagen <span class="badge" id="cart-badge">{{ $quote->getItemCount() }}</span>
                            </a>
                        </li>
                        <li class="dropdown {{ request()->is('account/*') ? 'active' : '' }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('customer::account.dashboard') }}"><span class="glyphicon glyphicon-user"></span> Gegevens</a></li>
                                <li><a href="{{ route('customer::account.favorites') }}"><span class="glyphicon glyphicon-heart"></span> Favorieten</a></li>
                                <li><a href="{{ route('customer::account.history') }}"><span class="glyphicon glyphicon-time"></span> Geschiedenis</a></li>
                                <li><a href="{{ route('customer::account.discountfile') }}"><span class="glyphicon glyphicon-euro"></span> Kortingsbestand</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('logout') }}"><span class="glyphicon glyphicon-off"></span> Loguit</a></li>
                            </ul>
                        </li>
                    @else
                        <li><a class="register-button" href="{{ route('auth.register') }}">Registreren</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>