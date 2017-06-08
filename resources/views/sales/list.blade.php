@extends('layouts.app')
@section('title', 'Sales')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sales
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Sale</a></li>
            <li class="active">List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {{ Session::get('message') }}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Sale List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Truck Number</th>
                                            <th>Date & Time</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Discount</th>
                                            <th>Bill Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($sales))
                                            @foreach($sales as $index=>$sale)
                                                <tr>
                                                    <td>{{ $index + $sales->firstItem() }}</td>
                                                    <td>{{ $sale->vehicle->reg_number }}</td>
                                                    <td>{{ $sale->date_time }}</td>
                                                    <td>{{ $sale->product->name }}</td>
                                                    @if($sale->measure_type == 1 && !empty($sale->quantity))
                                                        <td>{{ $sale->quantity }}</td>
                                                        <td>{{ $sale->rate }}</td>
                                                        <td>{{ $sale->discount }}</td>
                                                        <td>{{ $sale->total_amount }}</td>
                                                    @else
                                                        <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                        <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                        <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                        <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        @if(!empty($sales))
                                            {{ $sales->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.boxy -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection