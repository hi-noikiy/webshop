<h4>Series</h4>
<select id="serie-filter" class="search-filter" size="5">
    <option value="" {{ (!request('serie') ? 'selected' : '') }}>Geen serie filter</option>

    @foreach($series as $serie)
        <option {{ (request('serie') === $serie ? 'selected' : '') }} value="{{ $serie }}">{{ $serie }}</option>
    @endforeach
</select>

<script type="text/javascript">
    $('#serie-filter').on('change', function (e) {
        filter();
    });
</script>