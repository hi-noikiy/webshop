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
            <div id="brand-filter-wrapper"></div>

            <hr />

            <div id="serie-filter-wrapper"></div>

            <hr />

            <div id="type-filter-wrapper"></div>
        </div>

        <div class="col-md-9" id="results-wrapper"></div>
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

            $('#filters-wrapper .active').each(function () {
                console.log($(this).scrollTop());
            });
        });

        function filter () {
            $('#filter-overlay').fadeIn(300);

            var data = {
                brand : window.brandFilter,
                serie : window.serieFilter,
                type : window.typeFilter,
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
