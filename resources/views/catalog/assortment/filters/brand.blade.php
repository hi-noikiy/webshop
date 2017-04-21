<h4>Merk</h4>

<form>
    <select name="brand" id="brand-filter" onchange="form.submit()" class="filter-selector form-control">
        <option value="">--- Selecteer een merk ---</option>

        @foreach ($brands as $brand)
            <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>
                {{ $brand }}
            </option>
        @endforeach
    </select>
</form>