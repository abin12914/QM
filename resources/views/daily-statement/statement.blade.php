@extends('layouts.app')
@section('title', 'Transaction Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Transaction Statement
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transaction Statement</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <form action="{{ route('daily-statement-list-search') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">Start Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('from_date') }}" tabindex="1">
                                            @if(!empty($errors->first('from_date')))
                                                <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                                            <label for="to_date" class="control-label">End Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="to_date" id="to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('to_date') }}" tabindex="1">
                                            @if(!empty($errors->first('to_date')))
                                                <p style="color: red;" >{{$errors->first('to_date')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                <div class="box-header visible-print-block">
                    <h3>Transaction Statement</h3>
                </div>
                    <div class="box-header">
                        @if(empty($fromDate) && !empty($toDate))
                            <h4 style="float: left;">Date : {{ $toDate }}</h4>
                        @elseif(!empty($fromDate) && empty($toDate))
                            <h4 style="float: left;">Date : {{ $fromDate }}</h4>
                        @elseif(!empty($fromDate) && !empty($toDate))
                            <h4 style="float: left;">From : {{ $fromDate }} &nbsp;&nbsp;&nbsp; To : {{ $toDate }}</h4>
                        @endif
                        @if(!empty($restrictedDate) && $restrictedDate->copy()->addDay(7) < \Carbon\Carbon::now())
                            <h4 class="pull-right text-info">Next share allocation time period : {{ $restrictedDate->copy()->addDay()->format('d-m-Y') }} to {{ $restrictedDate->copy()->addDay(7)->format('d-m-Y') }}</h4>
                        @endif
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($sales))
                                    <tr>
                                        <td>Sales</td>
                                        <td>-</td>
                                        <td>{{ round($sales) }}</td>
                                    </tr>
                                @endif
                                @if(!empty($purchases))
                                    <tr>
                                        <td>Purchases and other expences</td>
                                        <td>{{ round($purchases) }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($labourWage))
                                    <tr>
                                        <td>Labour Wage</td>
                                        <td>{{ round($labourWage) }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($excavatorReadingRent))
                                    <tr>
                                        <td>Excavator reading rent</td>
                                        <td>{{ round($excavatorReadingRent) }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($jackhammerRent))
                                    <tr>
                                        <td>Jackhammer rent</td>
                                        <td>{{ round($jackhammerRent) }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($employeeSalary))
                                    <tr>
                                        <td>Employee Salary</td>
                                        <td>{{ round($employeeSalary) }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($excavatorMonthlyRent))
                                    <tr>
                                        <td>Excavator monthly rent</td>
                                        <td>{{ round($excavatorMonthlyRent) }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($royalty))
                                    <tr>
                                        <td>Royalty</td>
                                        <td>{{ round($royalty) }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <th>{{ round($totalDebit) }}</th>
                                    <th>{{ round($totalCredit) }}</th>
                                </tr>
                                <tr>
                                    @if($totalDebit <= $totalCredit)
                                        <th>{{ 'Balance[Profit]' }}</th>
                                        <th>{{ round(($totalCredit - $totalDebit)) }}</th>
                                        <th></th>
                                    @else
                                        <th>{{ 'Over expence[Loss]' }}</th>
                                        <th></th>
                                        <th>{{ round(($totalDebit - $totalCredit)) }}</th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @if(!empty($shareButtonFlag) && $shareButtonFlag)
                        <br>
                        <div class="row">
                            <div class="col-xs-5"></div>
                            <div class="col-xs-2">
                                <form action="{{ route('profit-loss-statement-list') }}" method="get" class="form-horizontal">
                                    <input type="hidden" name="from_date" value="{{ $fromDate }}">
                                    <input type="hidden" name="to_date" value="{{ $toDate }}">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4">Share Details</button>
                                </form>
                            </div>
                            <!-- /.col -->
                        </div><br>
                    @endif
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
        <div class="box-header with-border"></div><br>
        <div class="row no-print">
            <div class="col-md-6">
                <!-- BAR CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Income - Expence Chart</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="bincomeExpenceChart" style="height:500px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <!-- BAR CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Expences Chart</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="expencesChart" style="height:500px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <!-- ChartJS 1.0.1 -->
    <script src="/js/plugins/chartjs/Chart.min.js"></script>
    <script src="/js/statements/DailyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
    <script type="text/javascript">
        //data for income expnce chart
        var incomeExpenceChartData = {
        labels: ["{{ $fromDate }} To : {{ $toDate }}"],
            datasets: [
                @if(!empty($totalCredit))
                    {
                        label: "Income",
                        fillColor: "rgb(0, 153, 0)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($totalCredit) }}]
                    },
                @endif
                @if(!empty($totalDebit))
                    {
                        label: "Expence",
                        fillColor: "rgb(255,32,32)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($totalDebit) }}]
                    },
                @endif
                {
                    label: "",
                    fillColor: "rgb(255,255,255)",
                    strokeColor: "rgb(255,255,255)",
                    data: []
                },
            ]
        };

        //data for expnce chart
        var expenceChartData = {
        labels: ["{{ $fromDate }} To : {{ $toDate }}"],
            datasets: [
                @if(!empty($royalty))
                    {
                        label: "royalty",
                        fillColor: "rgb(255,32,32)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($royalty) }}]
                    },
                @endif
                @if(!empty($purchases))
                    {
                        label: "Purchases",
                        fillColor: "rgb(255,91,91)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($purchases) }}]
                    },
                @endif
                @if(!empty($labourWage))
                    {
                        label: "labourWage",
                        fillColor: "rgb(91,91,255)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($labourWage) }}]
                    },
                @endif
                @if(!empty($excavatorReadingRent))
                    {
                        label: "excavatorReadingRent",
                        fillColor: "rgb(255,255,0)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($excavatorReadingRent) }}]
                    },
                @endif
                @if(!empty($jackhammerRent))
                    {
                        label: "jackhammerRent",
                        fillColor: "rgb(255,0,127)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($jackhammerRent) }}]
                    },
                @endif
                @if(!empty($employeeSalary))
                    {
                        label: "employeeSalary",
                        fillColor: "rgb(204,0,0)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($employeeSalary) }}]
                    },
                @endif
                @if(!empty($excavatorMonthlyRent))
                    {
                        label: "excavatorMonthlyRent",
                        fillColor: "rgb(153,255,255)",
                        strokeColor: "rgb(255,255,255)",
                        data: [{{ round($excavatorMonthlyRent) }}]
                    },
                @endif
            ]
        };

        //Bar chart options used
        var barChartOptions = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - If there is a stroke on each bar
            barShowStroke: true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing: 1,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to make the chart responsive
            responsive: true,
            maintainAspectRatio: true
        };

        barChartOptions.datasetFill = false;

        //-------------
        //- BAR CHART - 1 - Income expence chart
        //-------------
        var incomeExpenceChartCanvas = $("#bincomeExpenceChart").get(0).getContext("2d");
        var incomeExpenceChart = new Chart(incomeExpenceChartCanvas);

        incomeExpenceChart.Bar(incomeExpenceChartData, barChartOptions);

        //-------------
        //- BAR CHART - 2 - expences chart
        //-------------
        var expencesChartCanvas = $("#expencesChart").get(0).getContext("2d");
        var expencesChart = new Chart(expencesChartCanvas);

        expencesChart.Bar(expenceChartData, barChartOptions);
    </script>
@endsection