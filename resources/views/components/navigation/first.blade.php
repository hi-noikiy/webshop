<nav class="navbar navbar-default navbar-static-top" id="navbar-first">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-links">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand {{ Route::is('home') ? 'visible-sm visible-xs' : '' }}" href="{{ route('home') }}">
                <img src="{{ asset('img/nav-logov2.png') }}" alt="Logo">
            </a>
        </div>

        <form class="navbar-form navbar-right" action="{{ url('search') }}" role="search">
            <div class="input-group">
                <input type="text" class="form-control" oninput="search.suggest(this)" name="q" placeholder="Zoeken"
                       value="{{ request('q') }}" data-suggest-url="{{ route('search::suggest') }}"
                       onblur="search.hideSuggest()" onfocus="search.suggest(this)" />

                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default suffix">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                </span>
            </div><!-- /input-group -->

            <div id="suggest-box" style="display: none;"></div>
        </form>
    </div>
</nav>