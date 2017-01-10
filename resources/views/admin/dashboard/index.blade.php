@extends('admin.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card card-2">
                    @include('admin.dashboard.components.product-import')
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card card-2">
                    @include('admin.dashboard.components.discount-import')
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8">
                <div class="card card-2">
                    @include('admin.dashboard.components.order-chart')
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card card-2">
                    @include('admin.dashboard.components.server-stats')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('document_end')
    <script type="text/javascript">
        function setProgressBar ($el, percentage, value, max) {
            $el.width(percentage + '%');
            $el.prop('aria-valuenow', value);
            $el.prop('aria-valuemax', max);

            if (percentage < 40) { $el.prop('class', 'progress-bar progress-bar-info'); }
            else if (percentage >= 40 && percentage < 60) { $el.prop('class', 'progress-bar progress-bar-success'); }
            else if (percentage >= 60 && percentage < 85) { $el.prop('class', 'progress-bar progress-bar-warning'); }
            else if (percentage >= 85) { $el.prop('class', 'progress-bar progress-bar-danger'); }
        }

        var chartColors = months.map(randomColor);

        // Get the context of the canvas element we want to select
        var orderChart = makeChart(
            document.getElementById('order-chart'),
            'bar',
            {
                labels: window.months,
                datasets: [
                    {
                        label: "Orders",
                        backgroundColor: chartColors,
                        borderColor: chartColors,
                        hoverBackgroundColor: chartColors,
                        hoverBorderColor: chartColors,
                        data: [0,0,0,0,0,0,0,0,0,0,0,0]
                    }
                ]
            },
            {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                            max: 70
                        }
                    }]
                }
            }
        );

        function getOrderChartData() {
            $.ajax({
                url: "{{ route('admin.dashboard::chart', [ 'type' => 'orders' ]) }}",
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

        $(document).ready(function() {
            getOrderChartData();
        });
    </script>

    <script type="text/javascript">
        /**
         * Parse the CPU load.
         */
        function parseCpuLoad (data) {
            var load = (data.load > data.max ? data.max : data.load);
            var width = (load / data.max) * 100;
            var $cpuProgressBar = $("#progress-bar-cpu");

            setProgressBar($cpuProgressBar, width, load, data.max);
        }

        /**
         * Parse the RAM load.
         */
        function parseRamLoad (data) {
            var width = 100 - data.freePercentage;
            var used = data.total - data.free;
            var $ramProgressBar = $("#progress-bar-ram");

            setProgressBar($ramProgressBar, width, used, data.total);
        }

        /**
         * Parse the disk usage.
         */
        function parseDiskUsage (data) {
            var width = (data.free / data.total) * 100;
            var used = data.total - data.free;
            var $diskProgressBar = $("#progress-bar-disk");

            setProgressBar($diskProgressBar, width, used, data.total);
        }

        /**
         * Get the server stats
         */
        function getServerStats() {
            $.ajax({
                url: "{{ route('admin.dashboard::stats') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    parseCpuLoad(data.cpu);
                    parseRamLoad(data.ram);
                    parseDiskUsage(data.disk);
                }
            });
        }

        setInterval(getServerStats, 5000);
        getServerStats();
    </script>
@endsection