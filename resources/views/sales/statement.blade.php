@extends('layouts.app')
@section('title', 'Sale Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sale Statement
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale Statement</li>
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
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <div class="box-header">
                        <form action="{{ route('sale-statement-list-search') }}" method="get" class="form-horizontal">
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
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('product_id')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">Product : </label>
                                            <select class="form-control" name="product_id" id="product_id" tabindex="3" style="width: 100%">
                                                @if(!empty($products) && (count($products) > 0))
                                                    <option value="">All products</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ ((old('product_id') == $product->id ) || $productId == $product->id) ? 'selected' : '' }}>{{ $product->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('product_id')))
                                                <p style="color: red;" >{{$errors->first('product_id')}}</p>
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
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header visible-print-block">
                        <h3>Transaction Statement</h3>
                    </div>
                    <div class="box-header">
                        @if(empty($fromDate) && !empty($toDate))
                            <h4>Date : {{ $toDate }}</h4>
                        @elseif(!empty($fromDate) && empty($toDate))
                            <h4>Date : {{ $fromDate }}</h4>
                        @elseif(!empty($fromDate) && !empty($toDate))
                            <h4>From : {{ $fromDate }} &nbsp;&nbsp;&nbsp; To : {{ $toDate }}</h4>
                        @endif
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th>Truck Type</th>
                                    <th>No Of Load</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicleTypes as $key => $vehicleType)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $vehicleType->name }}</td>
                                        <td>{{ $salesCount[$key][$vehicleType->id] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Total Load</th>
                                    <th></th>
                                    <th>{{ $totalSaleCount }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
        <div class="row no-print">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <!-- DONUT CHART -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sales Chart</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="pieChart" style="height:150px"></canvas>
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
    function getRandomColor() {
        var letters = 'FEDCBA9876543210';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
        @foreach($vehicleTypes as $key => $vehicleType)
            {
                value: {{ $salesCount[$key][$vehicleType->id] }},
                color: getRandomColor(),
                highlight: "#FA4D3B",
                label: "{{ $vehicleType->name }}"
            },
        @endforeach
    ];
    var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 10, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutBounce",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
</script>
@endsection