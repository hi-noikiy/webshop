<div class="panel wtg-panel-info">
    <div class="panel-heading text-center">Types</div>

    <div id="type-filter" class="list-group search-filter">
        <a data-filter="" href="#" class="list-group-item {{ (!request('type') ? 'active' : '') }}">Geen type filter</a>

        @foreach($types as $type)
            <a data-filter="{{ $type }}" href="#" class="list-group-item {{ (request('type') === $type ? 'active' : '') }}">{{ $type }}</a>
        @endforeach
    </div>
</div>

<script type="text/javascript">
    $('#type-filter a').on('click', function (e) {
        e.preventDefault();
        window.typeFilter = $(this).data('filter');
        filter();
    });
</script>