@extends('layouts.app')
@section('title', 'Profit-Loss Share')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Profit-Loss Share
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Profit-Loss Share</li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                <div class="box-header visible-print-block">
                    <h3>Profit-Loss Share</h3>
                </div>
                    <div class="box-header">
                        @if(!empty($fromDate) && !empty($toDate))
                            <h4 style="float: left;"><b>From : {{ $fromDate->format('d-m-Y') }} &nbsp;&nbsp;&nbsp; To : {{ $toDate->format('d-m-Y') }}</b></h4>
                        @endif
                        @if(!empty($restrictedDate) && $restrictedDate->copy()->addDay(7) < \Carbon\Carbon::now())
                            <h4 class="pull-right text-info">Next share allocation time period : {{ $restrictedDate->copy()->addDay()->format('d-m-Y') }} to {{ $restrictedDate->copy()->addDay(7)->format('d-m-Y') }}</h4>
                        @endif
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 2%"></th>
                                    <th style="width: 68%"></th>
                                    <th style="width: 15%">Debit</th>
                                    <th style="width: 15%">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0</td>
                                    <td>Total Expense And Income</td>
                                    <td>{{ round($totalDebit) }}</td>
                                    <td>{{ round($totalCredit) }}</td>
                                </tr>
                                <tr>
                                    <?php
                                        $fixedOwner = $owners->filter( function($value, $key) {
                                         return $value->share_type == 1;
                                        })->first();
                                    ?>
                                    <td>1</td>
                                    <td>{{ $fixedOwner->account->account_name }}<a href="#sale_based_profit_details">[Sale based profit]</a></td>
                                    <td></td>
                                    <td>{{ round($ownerShare[$fixedOwner->account_id]) }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <th></th>
                                    <th>Total</th>
                                    <th>{{ round($totalDebit) }}</th>
                                    <th>{{ round(($totalCredit + $ownerShare[$fixedOwner->account_id])) }}</th>
                                </tr>
                                <tr class="{{ ($balanceAmount < 0) ? "danger" : "success" }}">
                                    <th></th>
                                    @if($balanceAmount < 0)
                                        <th>Over expence[Loss]</th>
                                        <th>{{ round(($balanceAmount * -1)) }}</th>
                                        <th></th>
                                    @else
                                        <th>Balance[Profit]</th>
                                        <th></th>
                                        <th>{{ round($balanceAmount) }}</th>
                                    @endif
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
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                <div class="box-header visible-print-block">
                    <h3>Profit-Loss Share</h3>
                </div>
                    <div class="box-header">
                        @if(!empty($fromDate) && !empty($toDate))
                            <h4>End Sharing&emsp;[{{ $fromDate->format('d-m-Y') }} - {{ $toDate->format('d-m-Y') }}]</h4>
                        @endif
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 2%"></th>
                                    <th style="width: 68%"></th>
                                    <th style="width: 15%">Debit</th>
                                    <th style="width: 15%">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="{{ ($balanceAmount < 0) ? "danger" : "success" }}">
                                    <td>0</td>
                                    @if($balanceAmount > 0)
                                        <td>Balance[Profit]</td>
                                        <td>{{ round($balanceAmount) }}</td>
                                        <td></td>
                                    @else
                                        <th>Over expence[Loss]</th>
                                        <th></th>
                                        <th>{{ round(($balanceAmount* -1)) }}</th>
                                    @endif
                                </tr>
                                @foreach($owners as $key => $owner)
                                    <tr>
                                    @if($owner->share_type != 1)
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $owner->account->account_name }} [33.33%]</td>
                                        @if($ownerShare[$owner->account_id] < 0)
                                            <td>{{ round(($ownerShare[$owner->account_id] * -1)) }}</td>
                                            <td></td>
                                        @else
                                            <td></td>
                                            <td>{{ round($ownerShare[$owner->account_id]) }}</td>
                                        @endif
                                    @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <th></th>
                                    <th>Total</th>
                                    <th>{{ ($balanceAmount < 0) ? round(($balanceAmount * -1)) : round($balanceAmount) }}</th>
                                    <th>{{ ($balanceAmount < 0) ? round(($balanceAmount * -1)) : round($balanceAmount) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @if(!empty($shareConfirmButton))
                        @if($shareConfirmButton == 1)
                            <br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-6">
                                    <div class="alert alert-warning text-center">
                                        <b>Are sure to proceed with share options in the above table? Action is irriversable.</b>
                                    </div>
                                </div>
                                <div class="col-xs-5"></div>
                                <div class="col-xs-2">
                                    <form action="{{ route('profit-loss-statement-action') }}" method="post" id="share_form" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="from_date" value="{{ $fromDate->format('d-m-Y') }}">
                                        <input type="hidden" name="to_date" value="{{ $toDate->format('d-m-Y') }}">
                                        <button type="button" class="btn btn-primary btn-block btn-flat" id="share_button" tabindex="4">Allot Share Values</button>
                                    </form>
                                </div>
                                <!-- /.col -->
                            </div><br>
                        @elseif($shareConfirmButton == 2)
                            <br>
                            <div class="row no-print">
                                <div class="col-xs-5"></div>
                                <div class="col-xs-2">
                                    <div class="alert alert-default text-center bg-info">
                                        <i class="fa fa-2x fa-check-circle-o">
                                            <b class="text-info"> Processed</b>
                                        </i>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div><br>
                        @endif
                    @endif
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
        <div class="row" id="sale_based_profit_details">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header visible-print-block">
                        <h3>Transaction Statement</h3>
                    </div>
                    <div class="box-header">
                        @if(!empty($fromDate) && !empty($toDate))
                            <h4>Sale Based Profit Details&emsp;[{{ $fromDate->format('d-m-Y') }} - {{ $toDate->format('d-m-Y') }}]</h4>
                        @endif
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th>Truck Type</th>
                                    <th>No Of Load</th>
                                    <th>Quantity x Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicleTypes as $key => $vehicleType)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $vehicleType->name }}</td>
                                        <td>{{ $salesCount[$vehicleType->id] }}</td>
                                        <td>{{ $vehicleType->generic_quantity }} x {{ $ratePerFeet }}</td>
                                        <td>{{ $saleProfitAmount[$vehicleType->id] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total Load</th>
                                    <th></th>
                                    <th>{{ $totalSaleCount }}</th>
                                    <th></th>
                                    <th>{{ round($totalSaleProfitAmount) }}</th>
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
        <!-- Main row -->
    </section>
    <!-- /.content -->
    <!-- /.content -->
    <div class="modal modal modal-danger" id="share_confirmation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Confirm Action</h4>
                </div>
                <div class="modal-body">
                    <div id="modal_warning">
                        <div class="row">
                            <div class="col-sm-1">
                            </div>
                            <div class="col-sm-11">
                                <p>
                                    <b> Are sure to proceed with share options in the table? Action is irreversible.</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="share_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" id="share_modal_confirm" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endsection