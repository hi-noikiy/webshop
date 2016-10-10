@extends('master', ['pagetitle' => 'Webshop / Zoeken'])

@section('title')
    <h3>Zoeken</h3>
@endsection

@section('content')
    <div class="row" id="search-wrapper">
        <div id="filter-overlay">
            <i class="fa fa-refresh fa-4x fa-spin" aria-hidden="true"></i>
        </div>

        <div class="col-md-3" id="filters-wrapper">
            <?php $perPage = request('perPage') ?? 10; ?>
            <h4>
                Resultaten per pagina:
                <select id="results-per-page" class="search-limit">
                    @for ($i = 5; $i <= 20; $i+=5)
                        <option value="{{ $i }}" {{ ($perPage === $i ? 'selected' : '') }}>{{ $i }}</option>
                    @endfor
                </select>
            </h4>

            <hr />

            <div id="brand-filter-wrapper"></div>

            <hr />

            <div id="serie-filter-wrapper"></div>

            <hr />

            <div id="type-filter-wrapper"></div>
        </div>

        <div class="col-md-9" id="results-wrapper">
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var hash = top.location.hash.replace('#', '');
            var params = hash.split('&');
            var hashParams = {};

            for(var i = 0; i < params.length; i++){
                var propval = params[i].split('=');
                hashParams[propval[0]] = propval[1];
            }

            window.hashParams = hashParams;

            $.post({
                url: '{{ url('search/filter') }}',
                data: {
                    brand : hashParams.brand,
                    serie : hashParams.serie,
                    type : hashParams.type,
                    page : '{{ request('page') ?? 1 }}'
                },
                success: function (response) {
                    $('#results-wrapper').html(response.results);
                    $('#brand-filter-wrapper').html(response.brands);
                    $('#serie-filter-wrapper').html(response.series);
                    $('#type-filter-wrapper').html(response.types);

                    $('#filter-overlay').fadeOut(300);
                },
                errors: function () {
                    alert('Er is een fout opgetreden tijdens het laden van de resultaten');
                    $('#filter-overlay').fadeOut(300);
                }
            });
        });

        function filter () {
            $('#filter-overlay').fadeIn(300);

            var data = {
                brand : $('#brand-filter').val(),
                serie : $('#serie-filter').val(),
                type : $('#type-filter').val(),
                page : window.page
            };

            $.post({
                url: '{{ url('search/filter') }}',
                data: data,
                success: function (response) {
                    $('#results-wrapper').html(response.results);
                    $('#brand-filter-wrapper').html(response.brands);
                    $('#serie-filter-wrapper').html(response.series);
                    $('#type-filter-wrapper').html(response.types);

                    $('#filter-overlay').fadeOut(300);
                },
                errors: function () {
                    alert('Er is een fout opgetreden tijdens het laden van de resultaten');
                    $('#filter-overlay').fadeOut(300);
                }
            });
        }
    </script>
@endsection
