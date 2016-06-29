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
                    <div class="panel {{ $product_import->error ? 'wtg-panel-danger' : 'wtg-panel-success' }}">
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
                    <div class="panel {{ $discount_import->error ? 'wtg-panel-danger' : 'wtg-panel-success' }}">
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
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-2">CPU <i class="fa fa-tachometer" aria-hidden="true"></i></div>
                        <div class="col-sm-10">
                            <div class="progress">
                                <div id="progress-bar-cpu" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="4" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-2">RAM <i class="fa fa-cogs" aria-hidden="true"></i></div>
                        <div class="col-sm-10">
                            <div class="progress">
                                <div id="progress-bar-ram" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h3>
                        Orders per maand -

                        <select id="yearSelect" onchange="getOrderChartData()">
                            @foreach ($years as $year)
                                <option value="{{ $year['year'] }}">{{ $year['year'] }}</option>
                            @endforeach
                        </select>
                    </h3>

                    <canvas id="orderChart" height="400" style="width: 100%;"></canvas>
                </div>

                <div class="col-md-6">
                    <h3>
                        Bezoekers per browser per -

                        <select id="periodSelect" onchange="getBrowserChartData()">
                            <option value="365">Jaar</option>
                            <option value="31">Maand</option>
                            <option value="7">Week</option>
                            <option value="1">Dag</option>
                        </select>
                    </h3>

                    <canvas id="browserChart" height="400" style="width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraJS')
    <script type="text/javascript">
        Chart.defaults.global.maintainAspectRatio = false;
        // Get the context of the canvas element we want to select
        var orderChartContext = $('#orderChart');
        var orderChart = new Chart(orderChartContext, {
            type: 'bar',
            data:  {
                labels: ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'],
                datasets: [
                    {
                        label: "Orders",
                        backgroundColor: "#2196F3",
                        borderColor: "#2196F3",
                        hoverBackgroundColor: "#90CAF9",
                        hoverBorderColor: "#90CAF9",
                        data: [0,0,0,0,0,0,0,0,0,0,0,0]
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                            max: 70
                        }
                    }]
                }
            }
        });

        function getOrderChartData()
        {
            $.ajax({
                url: "/admin/api/chart/orders",
                type: "GET",
                data: { year : $('#yearSelect').val() },
                dataType: "json",
                success: function(response) {
                    var data = response.payload;
                    var chartData = [0,0,0,0,0,0,0,0,0,0,0,0];
                    var x, i;

                    for (i = 0; i < data.length; i++) {
                        // Replace the data from the empty array with data from the ajax response
                        chartData[data[i].month-1] = data[i].count;
                    }

                    for (x = 0; x < 12; x++) {
                        orderChart.data.datasets[0].data[x] = chartData[x];
                    }

                    orderChart.update();
                }
            });
        }

        var browserChartContext = $('#browserChart');
        var browsers        = {!! $browsers->toJson() !!};
        var browserLabels   = [];
        var browserSessions = [];

        for (var i = 0; i < browsers.length; i++) {
            browserLabels.push(browsers[i].browser);
            browserSessions.push(browsers[i].sessions);
        }

        var browserChart = new Chart(browserChartContext, {
            type: 'bar',
            data:  {
                labels: browserLabels,
                datasets: [
                    {
                        label: "Browsers",
                        backgroundColor: "#FF5722",
                        borderColor: "#FF5722",
                        hoverBackgroundColor: "#FF3D00",
                        hoverBorderColor: "#FF3D00",
                        data: browserSessions
                    }
                ],
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });

        function getBrowserChartData()
        {
            $.ajax({
                url: "/admin/api/chart/browsers",
                type: "GET",
                data: { days : $('#periodSelect').val() },
                dataType: "json",
                success: function(response) {
                    var data = response.payload;
                    var browserSessions = [];
                    var browserLabels = [];
                    var i;

                    for (i = 0; i < data.length; i++) {
                        browserLabels.push(data[i].browser);
                        browserSessions.push(data[i].sessions);
                    }

                    browserChart.data.labels           = browserLabels;
                    browserChart.data.datasets[0].data = browserSessions;

                    browserChart.update();
                }
            });
        }

        $(document).ready(function() {
            getOrderChartData();

        });
    </script>

    <script type="text/javascript">
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

        //setInterval(function() {
            //getServerLoad();
        //}, 5000);
        //getServerLoad();
    </script>
@endsection
