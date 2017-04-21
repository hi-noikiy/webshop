<h4>Type</h4>

<form>
    <input type="hidden" name="brand" value="{{ request('brand') }}">
    <input type="hidden" name="series" value="{{ request('series') }}">

    <select name="type" id="type-filter" onchange="form.submit()"
            class="filter-selector form-control" {{ !request('series') ? 'disabled' : '' }}>
        @if (request('brand'))
            <option value="">--- Selecteer een type ---</option>

            @foreach ($types as $type)
                <option value="{{ $type }}" class="filter-item" {{ request('type') === $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        @else
            <option>--- Selecteer eerst een serie ---</option>
        @endif
    </select>
</form>