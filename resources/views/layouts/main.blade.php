<!doctype html>
<html lang="nl">
    @include('components.head')

    <body>
        @yield('before_content')

        @if(!Auth::check())
            @include('components.login')
        @endif

        @include('components.navigation')

        @include('components.notifications')

        <div class="container content" id="content">
            <div class="row">
                <div class="col-md-12 bg-primary site-title">
                    @yield('title')
                </div>
            </div>

            @if( request()->is('/') )
                <div class="well well-sm text-center ie-only">
                    <h4>Voor de beste ervaring met onze website raden wij een moderne browser aan, zoals: <a href="https://www.google.com/chrome/browser/desktop/index.html" target="_blank">Google Chrome</a> of <a href="https://www.mozilla.org/nl/firefox/new/" target="_blank">Firefox</a>.</h4>
                </div>
            @endif

            @yield('content')
        </div>

        @include('components.footer')

        @yield('after_content')

        @if(!app()->environment('production'))
            <div style="position: fixed;bottom: 20px;right: 20px;background-color: red;color:#333;padding:5px;border:3px solid #333;">{{ ucfirst(app()->environment()) }}</div>
        @endif

        <script src="{{ mix('js/app.js') }}"></script>

        @yield('extraJS')
    </body>
</html>
