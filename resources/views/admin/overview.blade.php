@extends('master', ['pagetitle' => 'Admin / Overzicht'])

@section('title')
        <h3>Admin <small>overzicht</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <div class="row">
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
        </div>
@stop

@section('extraJS')
        <script type="text/javascript">
                getServerLoad();

                setInterval(function() {
                        getServerLoad();
                }, 15000);

                function getServerLoad() {
                        $.ajax({
                                url: "/admin/CPULoad",
                                type: "GET",
                                dataType: "json",
                                success: function(data) {
                                        var load    = (data.load > data.max ? data.max : data.load);
                                        var max     = data.max;
                                        var width   = (load / max) * 100;

                                        $("#progress-bar-cpu").width(width + '%');
                                        $("#progress-bar-cpu").prop('aria-valuenow', load);
                                        $("#progress-bar-cpu").prop('aria-valuemax', max);

                                        if (width < 40) { $('#progress-bar-cpu').prop('class', 'progress-bar progress-bar-info'); }
                                        else if (width >= 40 && width < 60) { $('#progress-bar-cpu').prop('class', 'progress-bar progress-bar-success'); }
                                        else if (width >= 60 && width < 85) { $('#progress-bar-cpu').prop('class', 'progress-bar progress-bar-warning'); }
                                        else if (width >= 85) { $('#progress-bar-cpu').prop('class', 'progress-bar progress-bar-danger'); }
                                }
                        });

                        $.ajax({
                                url: "/admin/RAMLoad",
                                type: "GET",
                                dataType: "json",
                                success: function(data) {
                                        var usedPercentage      = 100 - data.freePercentage;
                                        var free                = data.free;
                                        var total               = data.total;
                                        var used                = total - free;

                                        $("#progress-bar-ram").width(usedPercentage + '%');
                                        $("#progress-bar-ram").prop('aria-valuenow', used);
                                        $("#progress-bar-ram").prop('aria-valuemax', total);

                                        if (width < 40) { $('#progress-bar-ram').prop('class', 'progress-bar progress-bar-info'); }
                                        else if (width >= 40 && width < 60) { $('#progress-bar-ram').prop('class', 'progress-bar progress-bar-success'); }
                                        else if (width >= 60 && width < 85) { $('#progress-bar-ram').prop('class', 'progress-bar progress-bar-warning'); }
                                        else if (width >= 85) { $('#progress-bar-ram').prop('class', 'progress-bar progress-bar-danger'); }
                                }
                        });
                }
        </script>
@stop
