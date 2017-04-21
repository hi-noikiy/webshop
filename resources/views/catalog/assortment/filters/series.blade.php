<h4>Serie</h4>

<form>
    <input type="hidden" name="brand" value="{{ request('brand') }}">

    <select name="series" id="series-filter" onchange="form.submit()"
            class="filter-selector form-control" {{ !request('brand') ? 'disabled' : '' }}>
        @if (request('brand'))
            <option value="">--- Selecteer een serie ---</option>

            @foreach ($series as $serie)
                <option value="{{ $serie }}" class="filter-item" {{ request('series') === $serie ? 'selected' : '' }}>
                    {{ $serie }}
                </option>
            @endforeach
        @else
            <option>--- Selecteer eerst een merk ---</option>
        @endif
    </select>
</form>