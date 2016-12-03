<h3>
    <i class="fa fa-fw fa-book"></i> Orders per maand

    <select id="yearSelect" class="pull-right" onchange="getOrderChartData()">
        @foreach ($years as $year)
            <option value="{{ $year['year'] }}">{{ $year['year'] }}</option>
        @endforeach
    </select>
</h3>

<hr />

<div style="height: 300px;">
    <canvas id="order-chart"></canvas>
</div>