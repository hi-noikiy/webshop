<!doctype html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>WTG {{ isset($pagetitle) ? " | " . $pagetitle : '' }}</title>

        @yield('extraCSS')

        <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>
<body>
        <div class="background"></div>

        <nav class="navbar navbar-wtg navbar-static-top" role="navigation">
                <div class="wtg-nav-container">
                        <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand visible-xs" href="/">
                                        <img src="/img/nav-logo.png" alt="Logo">
                                </a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar">
                                <ul class="nav navbar-nav" id="nav-buttons">
                                        <li><a href="/">Home</a></li>
                                        <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Info <span class="caret"></span></a>
                                                <ul class="dropdown-menu" role="menu">
                                                        <li><a href="/about">Over ons</a></li>
                                                        <li><a href="/contact">Contact</a></li>
                                                 </ul>
                                        </li>
                                        <li><a href="/downloads">Downloads</a></li>
                                        <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Webshop <span class="caret"></span></a>
                                                <ul class="dropdown-menu" role="menu">
                                                        <li><a href="/webshop">Zoeken</a></li>
                                                        <li><a href="/specials">Acties</a></li>
                                                        <li><a href="/clearance">Opruiming</a></li>
                                                </ul>
                                        </li>
                                        @if(Auth::check() && Auth::user()->isAdmin)
                                                <li><a href="/admin">Admin</a></li>
                                        @endif
                                </ul>

                                <div class="navbar-right " id="nav-utils">
                                    <ul class="nav navbar-nav">
                                            @if(Auth::check())
                                                    <li><a href="/cart" style="height: 50px">Winkelwagen @if(Cart::count(false) > 0) <span class="badge">{{ Cart::count(false) }}</span> @endif</a></li>
                                                    <li class="dropdown">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <span class="caret"></span></a>
                                                            <ul class="dropdown-menu" role="menu">
                                                                    <li><a href="/account"><span class="glyphicon glyphicon-user"></span> Gegevens</a></li>
                                                                    <li><a href="/account/favorites"><span class="glyphicon glyphicon-heart"></span> Favorieten</a></li>
                                                                    <li><a href="/account/orderhistory"><span class="glyphicon glyphicon-time"></span> Geschiedenis</a></li>
                                                                    <li><a href="/account/discountfile"><span class="glyphicon glyphicon-euro"></span> Kortingsbestand</a></li>
                                                                    <li class="divider"></li>
                                                                    <li><a href="/logout"><span class="glyphicon glyphicon-off"></span> Loguit</a></li>
                                                            </ul>
                                                    </li>
                                            @else
                                                    <li><a href="/#loginModal">Login</a></li>
                                            @endif
                                    </ul>

                                    <br />

                                    <form action="/search" method="GET" class="navbar-form hidden-xs" role="search">
                                            {!! csrf_field() !!}
                                            <div class="form-group search-field has-feedback">
                                                    <input id="searchInput" value="{{ Input::get('q') }}" name="q" type="text" class="form-control" placeholder="Zoeken">
                                                    <i class="glyphicon glyphicon-search form-control-feedback"></i>
                                            </div>
                                    </form>
                                </div>
                        </div><!-- /.navbar-collapse -->
                </div><!-- /.container-->
        </nav>

        <header class="page-header hidden-xs">
                <div class="container">
                        <div class="col-md-4">
                                <a href="/"><img src="/img/logo.png" alt="Logo"></a>
                        </div>
                        <div class="col-md-8">
                                <h3>Sinds 1956 uw inkoop gemak van bodem tot dak.<br />Uw partner voor non-ferro, leidingsystemen, dakbedekking, sanitair en appendages.</h3>
                        </div>
                </div>
        </header>

        <div class="container content">

                <div class="row">
                        <div class="col-md-12 bg-primary site-title">
                                @yield('title')
                        </div>
                </div>

                @yield('content')

                <hr />

                <footer>
                        <div class="text-center">
                                Wiringa Technische Groothandel (1956 - {{ date("Y") }}) | <a href="http://lunamoonfang.me" target="_blank">Thomas Wiringa</a> - <a href="http://wiringa.nl/">wiringa.nl</a> | <a href="/licenses">licenties</a>
                                <p>
                                    <small>
                                        Al onze leveringen geschieden volgens onze algemene leveringsvoorwaarden, gedeponeerd bij de Kamer van Koophandel te Groningen onder nummer 02023871.
                                        <br />
                                        Een kopie van deze leveringsvoorwaarden zenden wij u op verzoek toe.
                                    </small>
                                </p>
                        </div>

                        <script src="{{ elixir('js/jquery.min.js') }}"></script>
                        <script src="{{ elixir('js/bootstrap.min.js') }}"></script>

                        <script type="text/javascript">
                                $("#searchInput").on('focus', function() {
                                        window.location.href = '/webshop';
                                });
                        </script>
                </footer>
        </div>
</body>
</html>
