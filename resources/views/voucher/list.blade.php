@extends('layouts.app')
@section('title', 'Vouchers')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Vouchers
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Voucher</a></li>
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
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="{{ Request::is('voucher/list/cash')? 'active' : '' }}"><a href="{{ Request::is('voucher/list/cash')? '#' : route('cash-voucher-list') }}">Cash Voucher</a></li>
                            <li class="{{ Request::is('voucher/list/credit')? 'active' : '' }}"><a href="{{ Request::is('voucher/list/credit')? '#' : route('credit-voucher-list') }}">Credit Vouchers</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ Request::is('voucher/list/cash')? 'active' : '' }} tab-pane" id="employee_tab">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date & Time</th>
                                                        <th>Account Name</th>
                                                        <th>Name</th>
                                                        <th>Transaction Type</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($cashVouchers) && count($cashVouchers) > 0)
                                                        @foreach($cashVouchers as $index => $cashVoucher)
                                                            <tr>
                                                                <td>{{ $index+1 }}</td>
                                                                <td>{{ $cashVoucher->date_time }}</td>
                                                                <td>{{ $cashVoucher->transaction->creditAccount->account_name }}</td>
                                                                <td>{{ $cashVoucher->transaction->creditAccount->accountDetail->name }}</td>
                                                                <td>{{ ($cashVoucher->transaction_type == 1) ? 'Debit' : 'Credit' }}</td>
                                                                <td>{{ $cashVoucher->amount }}</td>
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
                                                    @if(!empty($cashVouchers))
                                                        {{ $cashVouchers->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ Request::is('voucher/list/credit')? 'active' : '' }} tab-pane" id="excavators_tab">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date & Time</th>
                                                        <th>Debit Account</th>
                                                        <th>Credit Account</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($creditVouchers) && count($creditVouchers) > 0)
                                                    @foreach($creditVouchers as $index => $creditVoucher)
                                                        <tr>
                                                            <td>{{ $index+1 }}</td>
                                                            <td>{{ $creditVoucher->date_time }}</td>
                                                            <td>{{ $creditVoucher->transaction->creditAccount->account_name }}</td>
                                                            <td>{{ $creditVoucher->transaction->debitAccount->account_name }}</td>
                                                            <td>{{ $creditVoucher->amount }}</td>
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
                                                    @if(!empty($creditVouchers))
                                                        {{ $creditVouchers->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
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