@extends('layouts.app', ['pageSlug' => 'dashboard', 'page' => 'Dashboard', 'section' => ''])

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card card-chart">
                <div class="card-header ">
                    <h6 class="title d-inline"> TOTAL ADMISSIONS</h6>
                    <h4 class="card-category d-inline">Shows the list of Admissions/ER/OPD Visits</h4>
                    <div class="pull-right">
                        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                            <label class="btn btn-sm btn-primary btn-simple active" id="0">
                                <input type="radio" name="options" checked>
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Inpatients</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-single-02"></i>
                                </span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="1">
                                <input type="radio" class="d-none d-sm-none" name="options">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">ER Patients</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-gift-2"></i>
                                </span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="2">
                                <input type="radio" class="d-none" name="options">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">OPD Patients</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-tap-02"></i>
                                </span>
                            </label>
                            </div>
                        </div>
                    </div>
                <div class="card-body">

                 
                    <div class="chart-area"><canvas id="lineChartExample"></canvas>
                        {{-- <canvas id="chartBig1"></canvas> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            {{-- <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Last Month Income</h5>
                    <h3 class="card-title"><i class="tim-icons icon-money-coins text-primary"></i> format_money(semesterincomes) </h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLinePurple"></canvas>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="col-lg-4">
            {{-- <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Monthly Balance</h5>
                    <h3 class="card-title"><i class="tim-icons icon-bank text-info"></i> format_money(monthlybalance) }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="CountryChart"></canvas>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="col-lg-4">
            {{-- <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Expenditures Last Month</h5>
                    <h3 class="card-title"><i class="tim-icons icon-paper text-success"></i> format_money(semesterexpenses) </h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLineGreen"></canvas>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card card-tasks">
                {{-- <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Pending Sales</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">New Sale</a>
                        </div>
                    </div>
                </div> --}}
                <div class="card-body">
                    {{-- <div class="table-full-width table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                    <th>
                                        Products
                                    </th>
                                    <th>
                                        Paid out
                                    </th>
                                    <th>
                                        Total
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{--@foreach ($unfinishedsales as $sale)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ date('d-m-y', strtotime($sale->created_at)) }}</td>--}}
                                        {{--<td><a href="">{{ $sale->client->name }}<br>{{ $sale->client->document_type }}-{{ $sale->client->document_id }}</a></td>--}}
                                        {{--<td>{{ $sale->products->count() }}</td>--}}
                                        {{--<td>{{ format_money($sale->transactions->sum('amount')) }}</td>--}}
                                        {{--<td>{{ format_money($sale->products->sum('total_amount')) }}</td>--}}
                                        {{--<td class="td-actions text-right">--}}
                                            {{--<a href="{{ route('sales.show', ['sale' => $sale]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="View Sale">--}}
                                                {{--<i class="tim-icons icon-zoom-split"></i>--}}
                                            {{--</a>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card card-tasks">
                <div class="card-header">
                {{-- <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Latest Transactions</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#transactionModal">
                                New Transaction
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-full-width table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Category
                                    </th>
                                    <th>
                                        Title
                                    </th>
                                    <th>
                                        Medium
                                    </th>
                                    <th>
                                        Total
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            lastmonths
                            </tbody>
                        </table>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transactions.create', ['type' => 'payment']) }}" class="btn btn-sm btn-primary">Payment</a>
                        <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="btn btn-sm btn-primary">Income</a>
                        <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="btn btn-sm btn-primary">Expense</a>
                        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">Sale</a>
                        <a href="{{ route('transfer.create') }}" class="btn btn-sm btn-primary">Transfer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

<script type="text/javascript">
$(document).ready(function () {
  $('input[name="intervaltype"]').click(function () {
      $(this).tab('show');
      $(this).removeClass('active');
  });
})
</script>

    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

    {{--<script>--}}
        {{--var lastmonths = [];--}}

        {{--@foreach ($lastmonths as $id => $month)--}}
            {{--lastmonths.push('{{ strtoupper($month) }}')--}}
        {{--@endforeach--}}

        {{--var lastincomes = {{ $lastincomes }};--}}
        {{--var lastexpenses = {{ $lastexpenses }};--}}
        {{--var anualsales = {{ $anualsales }};--}}
        {{--var anualclients = {{ $anualclients }};--}}
        {{--var anualproducts = {{ $anualproducts }};--}}
        {{--var methods = [];--}}
        {{--var methods_stats = [];--}}

        {{--@foreach($monthlybalancebymethod as $method => $balance)--}}
            {{--methods.push('{{ $method }}');--}}
            {{--methods_stats.push('{{ $balance }}');--}}
        {{--@endforeach--}}

        {{--$(document).ready(function() {--}}
            {{--demo.initDashboardPageCharts();--}}
        {{--});--}}
    {{--</script>--}}

    <script>
        <!-- javascript init -->
// General configuration for the charts with Line gradientStroke
gradientChartOptionsConfiguration =  {
  maintainAspectRatio: false,
  legend: {
        display: false
   },

   tooltips: {
     backgroundColor: '#fff',
     titleFontColor: '#333',
     bodyFontColor: '#666',
     bodySpacing: 4,
     xPadding: 12,
     mode: "nearest",
     intersect: 0,
     position: "nearest"
   },
   responsive: true,
   scales:{
     yAxes: [{
       barPercentage: 1.6,
           gridLines: {
             drawBorder: false,
               color: 'rgba(29,140,248,0.0)',
               zeroLineColor: "transparent",
           },
           ticks: {
             suggestedMin:50,
             suggestedMax: 110,
               padding: 20,
               fontColor: "#9a9a9a"
           }
         }],

     xAxes: [{
       barPercentage: 1.6,
           gridLines: {
             drawBorder: false,
               color: 'rgba(220,53,69,0.1)',
               zeroLineColor: "transparent",
           },
           ticks: {
               padding: 20,
               fontColor: "#9a9a9a"
           }
         }]
     }
};

var ctx = document.getElementById("lineChartExample").getContext("2d");

var gradientStroke = ctx.createLinearGradient(0,230,0,50);

gradientStroke.addColorStop(1, 'rgba(72,72,176,0.2)');
gradientStroke.addColorStop(0.2, 'rgba(72,72,176,0.0)');
gradientStroke.addColorStop(0, 'rgba(119,52,169,0)'); //purple colors

var data = {
  labels: ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'],
  datasets: [{
    label: "Data",
    fill: true,
    backgroundColor: gradientStroke,
    borderColor: '#d048b6',
    borderWidth: 2,
    borderDash: [],
    borderDashOffset: 0.0,
    pointBackgroundColor: '#d048b6',
    pointBorderColor:'rgba(255,255,255,0)',
    pointHoverBackgroundColor: '#d048b6',
    pointBorderWidth: 20,
    pointHoverRadius: 4,
    pointHoverBorderWidth: 15,
    pointRadius: 4,
    data: [ 60,110,70,100, 75, 90, 80, 100, 70, 80, 120, 80],
  }]
};

var myChart = new Chart(ctx, {
  type: 'line',
  data: data,
  options: gradientChartOptionsConfiguration
});
    </script>
@endpush
