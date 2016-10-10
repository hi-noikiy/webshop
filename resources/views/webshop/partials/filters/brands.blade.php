<h4>Merken</h4>
<select id="brand-filter" class="search-filter" size="5">
    <option value="" {{ (!request('brand') ? 'selected' : '') }}>Geen merk filter</option>

    @foreach($brands as $brand)
        <option {{ (request('brand') === $brand ? 'selected' : '') }} value="{{ $brand }}">{{ $brand }}</option>
    @endforeach
</select>

<script type="text/javascript">
    $('#brand-filter').on('change', function (e) {
        filter();
    });
</script>