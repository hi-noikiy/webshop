<h4>Types</h4>
<select id="type-filter" class="search-filter" size="5">
    <option value="" {{ (!request('type') ? 'selected' : '') }}>Geen type filter</option>

    @foreach($types as $type)
        <option {{ (request('type') === $type ? 'selected' : '') }} value="{{ $type }}">{{ $type }}</option>
    @endforeach
</select>

<script type="text/javascript">
    $('#type-filter').on('change', function (e) {
        filter();
    });
</script>