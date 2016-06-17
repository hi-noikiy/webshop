@extends('master', ['pagetitle' => 'Admin / Cache stats'])

@section('title')
    <h3>Admin <small>cache statistieken</small></h3>
@stop

@section('content')
    @include('admin.nav')

    <br />

    <form action="{{ url('admin/cache/reset') }}" method="post">
        {{ csrf_field() }}

        <div class="modal fade" id="resetCacheModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Cache legen</h4>
                    </div>
                    <div class="modal-body">
                        <p>U staat op het punt om de cache te legen.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Verwijderen</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </form>

    <div class="text-center">
        <h3>OpCache <small><button data-target="#resetCacheModal" data-toggle="modal" class="btn btn-link">Reset cache</button></small></h3>
    </div>

    <hr />

    <div class="container">
        <div class="row text-center">
            <div class="col-md-6">
                <h4>Geheugen gebruik</h4>
                <canvas style="width: 100%; height: 200px;" id="memoryChart"></canvas>
            </div>

            <div class="col-md-6">
                <h4>Cache hitrate {{ round(($opcache_stats->get('hits') / ($opcache_stats->get('hits') + $opcache_stats->get('misses'))) * 100, 1) }}%</h4>
                <canvas style="width: 100%; height: 200px;" id="hitsChart"></canvas>
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="text-center">
                <h3>Information</h3>
            </div>

            <div class="col-md-4 col-md-offset-2">
                <table class="table table-striped">
                    <thead><tr><th colspan="2" class="text-center">Status</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td class="text-right"><span class="label label-{{ $opcache_enabled ? 'success' : 'danger' }}">{{ $opcache_enabled ? 'Enabled' : 'Disabled' }}</span></td>
                        </tr>

                        <tr>
                            <td>Cache full</td>
                            <td class="text-right"><span class="label label-{{ !$opcache_full ? 'success' : 'danger' }}">{{ !$opcache_full ? 'No' : 'Yes' }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <table class="table table-striped">
                    <thead><tr><th colspan="2" class="text-center">Statistics</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>Cached scripts</td>
                            <td class="text-right">{{ $opcache_stats->get('num_cached_scripts') }}</td>
                        </tr>

                        <tr>
                            <td>Cached keys</td>
                            <td class="text-right">{{ $opcache_stats->get('num_cached_keys') }}</td>
                        </tr>

                        <tr>
                            <td>Max cached keys</td>
                            <td class="text-right">{{ $opcache_stats->get('max_cached_keys') }}</td>
                        </tr>

                        <tr>
                            <td>Cache hits</td>
                            <td class="text-right">{{ $opcache_stats->get('hits') }}</td>
                        </tr>

                        <tr>
                            <td>Cache misses</td>
                            <td class="text-right">{{ $opcache_stats->get('misses') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('extraJS')
    <script>
        var memoryChart = new Chart($('#memoryChart'), {
            type: 'doughnut',
            data: {
                labels: ["MB Used", "MB Free", "MB Wasted"],
                datasets: [
                    {
                        data: [
                            {{ $opcache_memory->get('used') }},
                            {{ $opcache_memory->get('free') }},
                            {{ $opcache_memory->get('wasted') }}
                        ],
                        backgroundColor: [
                            "#F44336",
                            "#4CAF50",
                            "#FF9800"
                        ],
                        hoverBackgroundColor: [
                            "#FF5252",
                            "#69F0AE",
                            "#FFAB40"
                        ]
                    }
                ]
            }
        });

        var hitsChart = new Chart($('#hitsChart'), {
            type: 'doughnut',
            data: {
                labels: ["Misses", "Hits"],
                datasets: [
                    {
                        data: [
                            {{ $opcache_stats->get('misses') }},
                            {{ $opcache_stats->get('hits') }}
                        ],
                        backgroundColor: [
                            "#F44336",
                            "#4CAF50"
                        ],
                        hoverBackgroundColor: [
                            "#FF5252",
                            "#69F0AE"
                        ]
                    }
                ]
            }
        });
    </script>
@endsection