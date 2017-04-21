<div class="panel panel-primary" id="filters">
    <div class="panel-heading">
        <h3 class="panel-title">Filters</h3>
    </div>

    <div class="panel-body">
        <div class="row">
            <div id="brand-filter-container" class="col-sm-4">
                @include('catalog.assortment.filters.brand')
            </div>

            <div id="series-filter-container" class="col-sm-4">
                @include('catalog.assortment.filters.series')
            </div>

            <div id="type-filter-container" class="col-sm-4">
                @include('catalog.assortment.filters.type')
            </div>
        </div>
    </div>
</div>