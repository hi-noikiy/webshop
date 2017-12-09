<nav class="navbar navbar-expand-md navbar-light bg-transparent" id="navbar-first">
    <div class="container">
        <a class="navbar-brand" href="{{ routeIf('home') }}">
            @if (Route::is('home'))
                <img src="{{ asset('img/nav-logo.png') }}" alt="Logo">
            @else
                <img src="{{ asset('img/nav-logov3.png') }}" alt="Logo">
            @endif
        </a>

        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ Route::is('catalog.deals') ? 'active' : '' }}">
                <a class="nav-link btn btn-success" href="{{ routeIf('catalog.deals') }}">{{ trans('navigation.items.deals') }}</a>
            </li>
        </ul>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-links">
            <span class="navbar-toggler-icon"></span>
        </button>

        <form class="form-inline my-2 my-lg-0" action="{{ url('search') }}" role="search">
            <div class="input-group">
                <input type="text" class="form-control" oninput="search.suggest(this)" name="query" placeholder="{{ __('Zoeken') }}"
                       {{--value="{{ request('q') }}" data-suggest-url="{{ route('search::suggest') }}"--}}
                       onblur="search.hideSuggest()" onfocus="search.suggest(this)" />

                <span class="input-group-btn">
                    <button class="btn btn-outline-success" type="button">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                </span>
            </div>
            <div id="suggest-box" style="display: none;"></div>
        </form>
    </div>
</nav>

{{--<nav class="navbar navbar-default navbar-static-top" id="navbar-first">--}}
    {{--<div class="container">--}}
        {{--<div class="navbar-header">--}}
            {{--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-links">--}}
                {{--<span class="sr-only">Toggle navigation</span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
            {{--</button>--}}

            {{--<a class="navbar-brand" href="{{ route('home') }}">--}}
                {{--<img src="{{ asset('img/nav-logov2.png') }}" alt="Logo">--}}
            {{--</a>--}}
        {{--</div>--}}

        {{--<form class="navbar-form navbar-right" action="{{ url('search') }}" role="search">--}}
            {{--<div class="input-group">--}}
                {{--<input type="text" class="form-control" oninput="search.suggest(this)" name="q" placeholder="Zoeken"--}}
                       {{--value="{{ request('q') }}" data-suggest-url="{{ route('search::suggest') }}"--}}
                       {{--onblur="search.hideSuggest()" onfocus="search.suggest(this)" />--}}

                {{--<span class="input-group-btn">--}}
                    {{--<button type="submit" class="btn btn-default suffix">--}}
                        {{--<i class="fa fa-fw fa-search"></i>--}}
                    {{--</button>--}}
                {{--</span>--}}
            {{--</div>--}}

            {{--<div id="suggest-box" style="display: none;"></div>--}}
        {{--</form>--}}
    {{--</div>--}}
{{--</nav>--}}