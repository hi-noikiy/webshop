<div id="content-wrapper">
    @yield('before-content')

    <div class="hidden-sm hidden-xs">
        @include('admin.components.pre-content')
    </div>

    <div id="content" class="animated fadeIn">
        @yield('content')
    </div>

    @yield('after-content')
</div>