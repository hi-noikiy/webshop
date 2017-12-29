<header>
    <div id="header-bg"></div>

    @include('components.navigation')

    <div class="container">
        <carousel :slides="{{ $slides }}"></carousel>
    </div>
</header>