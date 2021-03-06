@extends('layouts.app')
@section('title', 'Voucher List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Voucher <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Voucher List</li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs  no-print">
                            <li class="{{ Request::is('voucher/list/cash')? 'active' : '' }}"><a href="{{ Request::is('voucher/list/cash')? '#' : route('cash-voucher-list') }}">Cash Voucher</a></li>
                            <li class="{{ Request::is('voucher/list/credit')? 'active' : '' }}"><a href="{{ Request::is('voucher/list/credit')? '#' : route('credit-voucher-list') }}">Credit Vouchers</a></li>
                            <li class="{{ Request::is('voucher/list/machine/through')? 'active' : '' }}"><a href="{{ Request::is('voucher/list/machine/through')? '#' : route('machine-through-voucher-list') }}">Through Vouchers</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ Request::is('voucher/list/cash')? 'active' : '' }} tab-pane" id="cash_tab">
                                <!-- box-header -->
                                <div class="box-header no-print">
                                    <form action="{{ route('cash-voucher-list') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                                                        <label for="transaction_type" class="control-label">Transaction Type : </label>
                                                        <select class="form-control" name="transaction_type" id="transaction_type" tabindex="3" style="width: 100%">
                                                            <option value="" {{ (empty($transactionType) || (empty(old('transaction_type')) && $transactionType == 0)) ? 'selected' : '' }}>Select transaction type</option>
                                                            <option value="2" {{ (!empty($transactionType) && ((old('transaction_type') == 2 ) || $transactionType == 2)) ? 'selected' : '' }}>Credit</option>
                                                            <option value="1" {{ (!empty($transactionType) && (old('transaction_type') == 1 || $transactionType == 1)) ? 'selected' : '' }}>Debit</option>
                                                        </select>
                                                        @if(!empty($errors->first('transaction_type')))
                                                            <p style="color: red;" >{{$errors->first('transaction_type')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6     {{ !empty($errors->first('cash_voucher_account_id')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_account_id" class="control-label">Account : </label>
                                                        <select class="form-control  account_id" name="cash_voucher_account_id" id="cash_voucher_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && (count($accounts) > 0))
                                                                <option value="">Select employee account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ ((old('cash_voucher_account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('cash_voucher_account_id')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_from_date')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_from_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="cash_voucher_from_date" id="cash_voucher_from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('cash_voucher_from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('cash_voucher_from_date')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_to_date')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_to_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="cash_voucher_to_date" id="cash_voucher_to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('cash_voucher_to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('cash_voucher_to_date')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_to_date')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div><br>
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-2">
                                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="10">Clear</button>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.form end -->
                                </div><br>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 15%;">Date / Ref. No.</th>
                                                        <th style="width: 15%;">Account Name</th>
                                                        <th style="width: 10%;">Name</th>
                                                        <th style="width: 20%;">Description</th>
                                                        <th style="width: 10%;">Debit</th>
                                                        <th style="width: 10%;">Credit</th>
                                                        <th style="width: 15%;" class="no-print">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($cashVouchers) && count($cashVouchers) > 0)
                                                        @foreach($cashVouchers as $index => $cashVoucher)
                                                            <tr>
                                                                <td>
                                                                    {{ $index + $cashVouchers->firstItem() }}
                                                                </td>
                                                                <td>
                                                                    {{ Carbon\Carbon::parse($cashVoucher->date_time)->format('d-m-Y') }}
                                                                    <i class="no-print"> / </i>
                                                                    <b class="no-print bg-info text-red">{{ $cashVoucher->transaction->id }}</b>
                                                                </td>
                                                                @if($cashVoucher->transaction_type == 1)
                                                                    <td>{{ $cashVoucher->transaction->creditAccount->account_name }}</td>
                                                                    <td>{{ $cashVoucher->transaction->creditAccount->accountDetail->name }}</td>
                                                                    <td>{{ $cashVoucher->transaction->particulars }}</td>
                                                                    <td>{{ $cashVoucher->amount }}</td>
                                                                    <td></td>
                                                                @elseif($cashVoucher->transaction_type == 2)
                                                                    <td>{{ $cashVoucher->transaction->debitAccount->account_name }}</td>
                                                                    <td>{{ $cashVoucher->transaction->debitAccount->accountDetail->name }}</td>
                                                                    <td>{{ $cashVoucher->transaction->particulars }}</td>
                                                                    <td></td>
                                                                    <td>{{ $cashVoucher->amount }}</td>
                                                                @else
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                @endif
                                                                <td class="no-print">
                                                                    @if($cashVoucher->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                        <form action="{{route('voucher-delete-action')}}" id="delete_{{ $cashVoucher->id }}" method="post" style="float: left;">
                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                            <input type="hidden" name="voucher_id" value="{{ $cashVoucher->id }}">
                                                                            <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($cashVoucher->date_time)->format('d-m-Y') }}">
                                                                            <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $cashVoucher->id }}" data-transaction-id="{{ $cashVoucher->transaction->id }}" type="button">
                                                                                <i class="fa fa-trash"> Delete</i>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                            <i class="fa fa-exclamation-circle"> No Access</i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if(Request::get('page') == $cashVouchers->lastPage() || $cashVouchers->lastPage() == 1)
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b>Total Amount</b></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b>{{ round($totalDebitAmount, 2) }}</b></td>
                                                                <td><b>{{ round($totalCreditAmount, 2) }}</b></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row no-print">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($cashVouchers))
                                                        {{ $cashVouchers->appends(Request::all())->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ Request::is('voucher/list/credit')? 'active' : '' }} tab-pane" id="credit_tab">
                                <!-- box-header -->
                                <div class="box-header no-print">
                                    <form action="{{ route('credit-voucher-list') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-4 {{ !empty($errors->first('credit_voucher_account_id')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_account_id" class="control-label">Account : </label>
                                                        <select class="form-control  account_id" name="credit_voucher_account_id" id="credit_voucher_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && (count($accounts) > 0))
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ ((old('credit_voucher_account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('credit_voucher_account_id')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('credit_voucher_from_date')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_from_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="credit_voucher_from_date" id="credit_voucher_from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('credit_voucher_from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('credit_voucher_from_date')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('credit_voucher_to_date')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_to_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="credit_voucher_to_date" id="credit_voucher_to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('credit_voucher_to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('credit_voucher_to_date')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_to_date')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div><br>
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-2">
                                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="10">Clear</button>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.form end -->
                                </div><br>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 15%;">Date / Ref. No.</th>
                                                        @if(!empty($accountId))
                                                            <th style="width: 15%;">Account</th>
                                                            <th style="width: 35%;">Description</th>
                                                            <th style="width: 10%;">Debit</th>
                                                            <th style="width: 10%;">Credit</th>
                                                        @else
                                                            <th style="width: 15%;">Supplier</th>
                                                            <th style="width: 15%;">Receiver</th>
                                                            <th style="width: 30%;">Description</th>
                                                            <th style="width: 10%;">Amount</th>
                                                        @endif
                                                        <th style="width: 10%;" class="no-print">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($creditVouchers) && count($creditVouchers) > 0)
                                                        @foreach($creditVouchers as $index => $creditVoucher)
                                                            <tr>
                                                                <td>
                                                                    {{ $index + $creditVouchers->firstItem() }}
                                                                </td>
                                                                <td>
                                                                    {{ Carbon\Carbon::parse($creditVoucher->date_time)->format('d-m-Y') }}
                                                                    <i class="no-print"> / </i>
                                                                    <b class="no-print bg-info text-red">{{ $creditVoucher->transaction->id }}</b>
                                                                </td>
                                                                @if($creditVoucher->transaction->debitAccount->id == $accountId)
                                                                    {{-- <td>
                                                                        {{ $creditVoucher->transaction->creditAccount->account_name }}
                                                                    </td> --}}
                                                                    <td class="bg-gray">
                                                                        {{ $creditVoucher->transaction->debitAccount->account_name }}
                                                                    </td>
                                                                    <td>{{ $creditVoucher->transaction->particulars }}</td>
                                                                    <td>{{ $creditVoucher->amount }}</td>
                                                                    <td></td>
                                                                @elseif($creditVoucher->transaction->creditAccount->id == $accountId)
                                                                    <td class="bg-warning">
                                                                        {{ $creditVoucher->transaction->creditAccount->account_name }}
                                                                    </td>
                                                                    {{-- <td>
                                                                        {{ $creditVoucher->transaction->debitAccount->account_name }}
                                                                    </td> --}}
                                                                    <td>{{ $creditVoucher->transaction->particulars }}</td>
                                                                    <td></td>
                                                                    <td>{{ $creditVoucher->amount }}</td>
                                                                @else
                                                                    <td>{{ $creditVoucher->transaction->creditAccount->account_name }}</td>
                                                                    <td>{{ $creditVoucher->transaction->debitAccount->account_name }}</td>
                                                                    <td>{{ $creditVoucher->transaction->particulars }}</td>
                                                                    <td>{{ $creditVoucher->amount }}</td>
                                                                @endif
                                                                <td class="no-print">
                                                                    @if($creditVoucher->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                        <form action="{{route('voucher-delete-action')}}" id="delete_{{ $creditVoucher->id }}" method="post" style="float: left;">
                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                            <input type="hidden" name="voucher_id" value="{{ $creditVoucher->id }}">
                                                                            <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($creditVoucher->date_time)->format('d-m-Y') }}">
                                                                            <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $creditVoucher->id }}" data-transaction-id="{{ $creditVoucher->transaction->id }}" type="button">
                                                                                <i class="fa fa-trash"> Delete</i>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                            <i class="fa fa-exclamation-circle"> No Access</i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if(!empty($accountId) && (Request::get('page') == $creditVouchers->lastPage() || $creditVouchers->lastPage() == 1))
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td><b>Total Amount</b></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b>{{ round($totalDebitAmount, 2) }}</b></td>
                                                                <td><b>{{ round($totalCreditAmount, 2) }}</b></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row no-print">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($creditVouchers))
                                                        {{ $creditVouchers->appends(Request::all())->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ Request::is('voucher/list/machine/through')? 'active' : '' }} tab-pane" id="machine_through_tab">
                                <!-- box-header -->
                                <div class="box-header no-print">
                                    <form action="{{ route('machine-through-voucher-list') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-4 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_account_id" class="control-label">Account : </label>
                                                        <select class="form-control account_id" name="account_id" id="machine_voucher_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && (count($accounts) > 0))
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ ((old('account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('account_id')))
                                                            <p style="color: red;" >{{$errors->first('account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_from_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="machine_voucher_from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('from_date')))
                                                            <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_to_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="to_date" id="machine_voucher_to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('to_date')))
                                                            <p style="color: red;" >{{$errors->first('to_date')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-4 {{ !empty($errors->first('machine_class')) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_machine_class" class="control-label">Machine Class : </label>
                                                        <select class="form-control machine" name="machine_class" id="machine_voucher_machine_class" tabindex="3" style="width: 100%">
                                                            <option value="">Select machine class</option>
                                                            <option value="1" {{ ((old('machine_class') == 1 ) || $accountId == 1) ? 'selected' : '' }}>Excavator</option>
                                                            <option value="2" {{ ((old('machine_class') == 1 ) || $accountId == 1) ? 'selected' : '' }}>Jackhammer</option>
                                                        </select>
                                                        @if(!empty($errors->first('machine_class')))
                                                            <p style="color: red;" >{{$errors->first('machine_class')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('excavator_id')) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_excavator_id" class="control-label">Excavator : </label>
                                                        <select class="form-control machine" name="excavator_id" id="machine_voucher_excavator_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($excavators) && (count($excavators) > 0))
                                                                <option value="">Select excavators</option>
                                                                @foreach($excavators as $excavator)
                                                                    <option value="{{ $excavator->id }}" {{ ((old('excavator_id') == $excavator->id ) || $excavatorId == $excavator->id) ? 'selected' : '' }}>{{ $excavator->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('excavator_id')))
                                                            <p style="color: red;" >{{$errors->first('excavator_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('jackhammer_id')) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_jackhammer_id" class="control-label">Jackhammer : </label>
                                                        <select class="form-control machine" name="jackhammer_id" id="machine_voucher_jackhammer_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($jackhammers) && (count($jackhammers) > 0))
                                                                <option value="">Select jackhammer</option>
                                                                @foreach($jackhammers as $jackhammer)
                                                                    <option value="{{ $jackhammer->id }}" {{ ((old('jackhammer_id') == $jackhammer->id ) || $jackhammerId == $jackhammer->id) ? 'selected' : '' }}>{{ $jackhammer->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('jackhammer_id')))
                                                            <p style="color: red;" >{{$errors->first('jackhammer_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div><br>
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-2">
                                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="10">Clear</button>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.form end -->
                                </div><br>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 15%;">Date / Ref. No.</th>
                                                        <th style="width: 10%;">Machine</th>
                                                        <th style="width: 12%;">Supplier</th>
                                                        <th style="width: 12%;">Receiver</th>
                                                        <th style="width: 28%;">Description</th>
                                                        <th style="width: 8%;">Amount</th>
                                                        <th style="width: 10%;" class="no-print">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($machineVouchers) && count($machineVouchers) > 0)
                                                        @foreach($machineVouchers as $index => $machineVoucher)
                                                            <tr>
                                                                <td>{{ $index + $machineVouchers->firstItem() }}</td>
                                                                <td>
                                                                    {{ Carbon\Carbon::parse($machineVoucher->date_time)->format('d-m-Y') }}
                                                                    <i class="no-print"> / </i>
                                                                    <b class="no-print bg-info text-red">{{ $machineVoucher->transaction->id }}</b>
                                                                </td>
                                                                @if(!empty($machineVoucher->excavator_id))
                                                                    <td>{{ $machineVoucher->excavator->name }}</td>
                                                                @elseif(!empty($machineVoucher->jackhammer_id))
                                                                    <td>{{ $machineVoucher->jackhammer->name }}</td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if($machineVoucher->transaction->debitAccount->id == $accountId)
                                                                    <td>{{ $machineVoucher->transaction->creditAccount->account_name }}</td>
                                                                    <td class="bg-gray">{{ $machineVoucher->transaction->debitAccount->account_name }}</td>
                                                                @elseif($machineVoucher->transaction->creditAccount->id == $accountId)
                                                                    <td class="bg-gray">{{ $machineVoucher->transaction->creditAccount->account_name }}</td>
                                                                    <td>{{ $machineVoucher->transaction->debitAccount->account_name }}</td>
                                                                @else
                                                                    <td>{{ $machineVoucher->transaction->creditAccount->account_name }}</td>
                                                                    <td>{{ $machineVoucher->transaction->debitAccount->account_name }}</td>
                                                                @endif
                                                                <td>{{ $machineVoucher->transaction->particulars }}</td>
                                                                <td>{{ $machineVoucher->amount }}</td>
                                                                <td class="no-print">
                                                                    @if($machineVoucher->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                        <form action="{{route('voucher-delete-action')}}" id="delete_{{ $machineVoucher->id }}" method="post" style="float: left;">
                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                            <input type="hidden" name="voucher_id" value="{{ $machineVoucher->id }}">
                                                                            <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($machineVoucher->date_time)->format('d-m-Y') }}">
                                                                            <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $machineVoucher->id }}" data-transaction-id="{{ $machineVoucher->transaction->id }}" type="button">
                                                                                <i class="fa fa-trash"> Delete</i>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                            <i class="fa fa-exclamation-circle"> No Access</i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if(!empty($machineVouchers) && (Request::get('page') == $machineVouchers->lastPage() || $machineVouchers->lastPage() == 1))
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b>Total Amount</b></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b>{{ round($totalAmount, 2) }}</b></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row no-print">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($machineVouchers))
                                                        {{ $machineVouchers->appends(Request::all())->links() }}
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
<div class="modal modal modal-danger" id="delete_confirmation_modal">
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
                        <div class="col-sm-2">
                            <b id="modal_warning_record_id" class="pull-right"></b>
                        </div>
                        <div class="col-sm-10">
                            <p>
                                <b> Are you sure to delete this record?</b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="delete_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                <button type="button" id="delete_confirmation_modal_confirm" class="btn btn-primary" data-delete-modal-id="0" data-dismiss="modal">Confirm</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection
@section('scripts')
    <script src="/js/list/voucher.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection