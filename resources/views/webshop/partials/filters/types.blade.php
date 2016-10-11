<h4>Types</h4>
<div id="type-filter" class="list-group search-filter">
    <a data-filter="" href="#" class="list-group-item {{ (!request('type') ? 'active' : '') }}">Geen type filter</a>

    @foreach($types as $type)
        <a data-filter="{{ $type }}" href="#" class="list-group-item {{ (request('type') === $type ? 'active' : '') }}">{{ $type }}</a>
    @endforeach
</div>

<script type="text/javascript">
    $('#type-filter a').on('click', function (e) {
        e.preventDefault();
        window.typeFilter = $(this).data('filter');
        filter();
    });
</script>