<!doctype html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>WTG</title>

        @yield('extraCSS')

        <style>
                @import url(//fonts.googleapis.com/css?family=Lato:300);
                @import url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css);
                @import url(//localhost:8000/css/app.min.css);
        </style>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>
<body>
        <nav class="navbar navbar-wtg navbar-static-top" role="navigation">
                <div class="container">
                        <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="/">
                                        WTG
                                </a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar">
                                <ul class="nav navbar-nav">
                                        <li class="@if( Route::current()->getUri() === '/' ) active @endif"><a href="/">Home</a></li>
                                        <li class="dropdown @if( Route::current()->getUri() === 'about' || Route::current()->getUri() === 'contact' ) active @endif">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Info <span class="caret"></span></a>
                                                <ul class="dropdown-menu" role="menu">
                                                        <li><a href="/about">Over ons</a></li>
                                                        <li><a href="/contact">Contact</a></li>
                                                 </ul>
                                        </li>
                                        <li class="@if( Route::current()->getUri() === 'downloads' ) active @endif"><a href="/downloads">Downloads</a></li>
                                        <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Webshop <span class="caret"></span></a>
                                                <ul class="dropdown-menu" role="menu">
                                                        <li><a href="/webshop">Bestellen</a></li>
                                                        <li><a href="/special">Acties</a></li>
                                                        <li><a href="/clearance">Opruiming</a></li>
                                                </ul>
                                        </li>
                                </ul>

                                <ul class="nav navbar-nav navbar-right">
                                        <li><a href="/login">Login</a></li>
                                </ul>

                                <form action="/search" method="POST" class="navbar-form navbar-right hidden-xs" role="search">
                                        <div class="form-group search-field has-feedback">
                                                <input id="searchInput" type="text" class="form-control" placeholder="Zoeken" data-toggle="tooltip" data-placement="bottom" title="Druk op ENTER om te zoeken">
                                                <i class="glyphicon glyphicon-search form-control-feedback"></i>
                                        </div>
                                </form>
                        </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
        </nav>

        <header class="page-header">
                <div class="container">
                        <div class="col-md-4">
                                <a href="/"><img src="/img/logo.png" alt="Logo"></a>
                        </div>
                        <div class="col-md-6">
                                <h3>Sinds 1956 uw inkoop gemak van bodem tot dak. Uw partner voor non-ferro, leidingsystemen, dakbedekking, sanitair en appendages.</h3>
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
                                <div class="glyphicon glyphicon-copyright-mark"></div> Wiringa Technische Groothandel (2014 - 2015) - <a href="#">Thomas Wiringa</a> - <a href="http://wiringa.nl/">wiringa.nl</a>
                                <p><small>Alle leveringen geschieden volgens onze algemene verkoopvoorwaarden gedeponeerd ter griffie van de arrondissementsrechtbank te Groningen onder nummer HK-255/93.</small></p>
                        </div>

                        <script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
                        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

                        @yield('extraJS')

                        <script type="text/javascript">
                                $(function () {
                                        $('[data-toggle="tooltip"]').tooltip();

                                        $('.search-field').hover(function() {
                                                $('#searchInput').focus();
                                        }, function() {
                                                $('#searchInput').blur();
                                        });
                                })
                        </script>
                </footer>
        </div>
</body>
</html>
