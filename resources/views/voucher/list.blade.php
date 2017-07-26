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
                            <div class="{{ Request::is('voucher/list/cash')? 'active' : '' }} tab-pane" id="cash_tab">
                                <!-- box-header -->
                                <div class="box-header">
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
                                                        <select class="form-control" name="cash_voucher_account_id" id="cash_voucher_account_id" tabindex="3" style="width: 100%">
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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                                @if($cashVoucher->transaction_type == 1)
                                                                    <td>{{ $cashVoucher->transaction->creditAccount->account_name }}</td>
                                                                    <td>{{ $cashVoucher->transaction->creditAccount->accountDetail->name }}</td>
                                                                @elseif($cashVoucher->transaction_type == 2)
                                                                    <td>{{ $cashVoucher->transaction->debitAccount->account_name }}</td>
                                                                    <td>{{ $cashVoucher->transaction->debitAccount->accountDetail->name }}</td>
                                                                @else
                                                                    <td></td>
                                                                    <td></td>
                                                                @endif
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
                                <div class="box-header">
                                    <form action="{{ route('credit-voucher-list') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-4 {{ !empty($errors->first('credit_voucher_account_id')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_account_id" class="control-label">Account : </label>
                                                        <select class="form-control" name="credit_voucher_account_id" id="credit_voucher_account_id" tabindex="3" style="width: 100%">
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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                            @if($creditVoucher->transaction->debitAccount->id == $accountId)
                                                                <td>{{ $creditVoucher->transaction->creditAccount->account_name }}</td>
                                                                <td class="bg-gray">{{ $creditVoucher->transaction->debitAccount->account_name }}</td>
                                                            @elseif($creditVoucher->transaction->creditAccount->id == $accountId)
                                                                <td class="bg-gray">{{ $creditVoucher->transaction->creditAccount->account_name }}</td>
                                                                <td>{{ $creditVoucher->transaction->debitAccount->account_name }}</td>
                                                            @else
                                                                <td>{{ $creditVoucher->transaction->creditAccount->account_name }}</td>
                                                                <td>{{ $creditVoucher->transaction->debitAccount->account_name }}</td>
                                                            @endif
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
@section('scripts')
    <script src="/js/list/voucher.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection