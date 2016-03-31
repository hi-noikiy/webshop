@extends('master', ['pagetitle' => 'Admin / Overzicht'])

@section('title')
    <h3>Admin <small>overzicht</small></h3>
@endsection

@section('content')
    @include('admin.nav')

    <div class="row">
        <div class="col-md-12">
            <h3>Import status</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel {{ $product_import->error ? 'wtg-panel-danger' : 'panel-primary' }}">
                        <div class="panel-heading">Product import</div>
                        <table class="table">
                            <tbody>
                            <tr>
                                <td>Laatste update</td>
                                <td>{{ $product_import->updated_at->getTimestamp() === -62169984000 ? $product_import->created_at : $product_import->updated_at }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>{!! $product_import->content !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel {{ $discount_import->error ? 'wtg-panel-danger' : 'panel-primary' }}">
                        <div class="panel-heading">Korting import</div>
                        <table class="table">
                            <tbody>
                            <tr>
                                <td>Laatste update</td>
                                <td>{{ $discount_import->updated_at->getTimestamp() === -62169984000 ? $discount_import->created_at : $discount_import->updated_at }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>{!! $discount_import->content !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h3>System Health</h3>
            <div class="row">
                <div class="col-sm-2">CPU gebruik:</div>
                <div class="col-sm-10">
                    <div class="progress">
                        <div id="progress-bar-cpu" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="4" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">RAM gebruik:</div>
                <div class="col-sm-10">
                    <div class="progress">
                        <div id="progress-bar-ram" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h3>
                Orders per maand -

                <select id="yearSelect" onchange="getChartData()">
                    @foreach ($years as $year)
                        <option value="{{ $year['year'] }}">{{ $year['year'] }}</option>
                    @endforeach
                </select>
            </h3>

            <canvas id="orderChart" style="height: 500px; width: 100%;"></canvas>
        </div>
    </div>
@endsection

@section('extraJS')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

    <script type="text/javascript">
        // Get the context of the canvas element we want to select
        var ctx = document.getElementById("orderChart").getContext("2d");

        var orderChart = new Chart(ctx).Bar({
            labels: ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'],
            datasets: [
                {
                    label: "Orders",
                    fillColor: "#2196F3",
                    strokeColor: "#2196F3",
                    highlightFill: "#90CAF9",
                    highlightStroke: "#90CAF9",
                    data: [0,0,0,0,0,0,0,0,0,0,0,0]
                }
            ]
        });

        setInterval(function() {
            getServerLoad();
        }, 10000);

        function getServerLoad()
        {
            $.ajax({
                url: "/admin/api/cpu",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var load            = (data.load > data.max ? data.max : data.load);
                    var max             = data.max;
                    var width           = (load / max) * 100;
                    var cpuProgressBar  =$("#progress-bar-cpu");

                    $(cpuProgressBar).width(width + '%');
                    $(cpuProgressBar).prop('aria-valuenow', load);
                    $(cpuProgressBar).prop('aria-valuemax', max);

                    if (width < 40) { $(cpuProgressBar).prop('class', 'progress-bar progress-bar-info'); }
                    else if (width >= 40 && width < 60) { $(cpuProgressBar).prop('class', 'progress-bar progress-bar-success'); }
                    else if (width >= 60 && width < 85) { $(cpuProgressBar).prop('class', 'progress-bar progress-bar-warning'); }
                    else if (width >= 85) { $(cpuProgressBar).prop('class', 'progress-bar progress-bar-danger'); }
                }
            });

            $.ajax({
                url: "/admin/api/ram",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var width               = 100 - data.freePercentage;
                    var free                = data.free;
                    var total               = data.total;
                    var used                = total - free;
                    var ramProgressBar      = $("#progress-bar-ram");

                    $(ramProgressBar).width(width + '%');
                    $(ramProgressBar).prop('aria-valuenow', used);
                    $(ramProgressBar).prop('aria-valuemax', total);

                    if (width < 40) { $(ramProgressBar).prop('class', 'progress-bar progress-bar-info'); }
                    else if (width >= 40 && width < 60) { $(ramProgressBar).prop('class', 'progress-bar progress-bar-success'); }
                    else if (width >= 60 && width < 85) { $(ramProgressBar).prop('class', 'progress-bar progress-bar-warning'); }
                    else if (width >= 85) { $(ramProgressBar).prop('class', 'progress-bar progress-bar-danger'); }
                }
            });
        }

        function getChartData()
        {
            $.ajax({
                url: "/admin/api/chart/orders",
                type: "GET",
                data: { year : $('#yearSelect').val() },
                dataType: "json",
                success: function(response) {
                    var chartData = [0,0,0,0,0,0,0,0,0,0,0,0];

                    for (var i = 0; i < response.length; i++) {
                        // Replace the data from the empty array with data from the ajax response
                        chartData[response[i].month-1] = response[i].count;
                    }

                    for (var x = 0; x < 12; x++) {
                        orderChart.datasets[0].bars[x].value = chartData[x];
                    }

                    orderChart.update();
                }
            });
        }

        getServerLoad();
        getChartData();
    </script>
@endsection
