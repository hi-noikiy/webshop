<h4>Series</h4>
<div id="serie-filter" class="list-group search-filter">
    <a data-filter="" href="#" class="list-group-item {{ (!request('serie') ? 'active' : '') }}">Geen serie filter</a>

    @foreach($series as $serie)
        <a data-filter="{{ $serie }}" href="#" class="list-group-item {{ (request('serie') === $serie ? 'active' : '') }}">{{ $serie }}</a>
    @endforeach
</div>

<script type="text/javascript">
    $('#serie-filter a').on('click', function (e) {
        e.preventDefault();
        window.serieFilter = $(this).data('filter');
        filter();
    });
</script>