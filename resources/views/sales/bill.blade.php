@extends('layouts.app')
@section('title', 'Sale Bill')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sales<small>Bill</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale Bill</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> JSJ Quarry Products
                    <small class="pull-right">Date: {{ $date }}</small>
                </h2>
            </div>
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Bill To
                <address>
                    <strong>{{ $customer }}</strong><br>
                    {{ $address }}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                Ship To
                <address>
                <strong>{{ $customer }}</strong><br>
                    {{ $address }}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Invoice No&emsp;&emsp;: #{{ $saleId }}</b><br>
                <b>Sale Type &emsp;&emsp;&nbsp;: </b>{{ $saleType }} <br>
                <b>Truck Number : </b>{{ $sale->vehicle->reg_number }}
            </div>
            <!-- /.col -->
        </div><br>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Discount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $sale->product->name }}</td>
                            <td>{{ $sale->rate }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>{{ $sale->discount }}</td>
                            <td>{{ $sale->total_amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
            <br>
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr
                    jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                </p>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <br>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>{{ $sale->total_amount }}</td>
                        </tr>
                        <tr>
                            <th>Taxable Value:</th>
                            <td>0</td>
                        </tr>
                        <tr>
                            <th>Tax (0.0%)</th>
                            <td>0</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td>{{ $sale->total_amount }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/list/sales.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection