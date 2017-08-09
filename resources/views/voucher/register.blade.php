@extends('layouts.app')
@section('title', 'Voucher Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Voucher
            <small>Registration</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Voucher Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                </h4>
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger" id="alert-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <!-- nav-tabs-custom -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="{{ ((old('tab_flag') == 'cash_voucher') || (empty(Session::get('controller_tab_flag')) && empty(old('tab_flag'))) || (Session::get('controller_tab_flag') == 'cash_voucher')) ? 'active' : '' }}"><a href="#cash_voucher_tab" data-toggle="tab">Cash Voucher</a></li>
                            <li class="{{ (old('tab_flag') == 'credit_voucher' || (!empty(Session::get('controller_tab_flag')) && (Session::get('controller_tab_flag') == 'credit_voucher'))) ? 'active' : '' }}"><a href="#credit_voucher_tab" data-toggle="tab">Credit Voucher</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ (old('tab_flag') == 'cash_voucher') || (empty(Session::get('controller_tab_flag')) && empty(old('tab_flag'))) || (Session::get('controller_tab_flag') == 'cash_voucher') ? 'active' : '' }} tab-pane" id="cash_voucher_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('cash-voucher-register-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="cash_voucher">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_date')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_date" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="cash_voucher_date" id="cash_voucher_date" placeholder="Date" value="{{ old('cash_voucher_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('cash_voucher_date')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_time')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_time" class="control-label">Time : </label>
                                                        <div class="bootstrap-timepicker">
                                                            <input type="text" class="form-control timepicker" name="cash_voucher_time" id="cash_voucher_time" placeholder="Time" value="{{ old('cash_voucher_time') }}" tabindex="2">
                                                        </div>
                                                        @if(!empty($errors->first('cash_voucher_time')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_time')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_account_id')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_account_id" class="control-label">Account : </label>
                                                        <select class="form-control account_select" name="cash_voucher_account_id" id="cash_voucher_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && count($accounts) > 0)
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ (old('cash_voucher_account_id') == $account->id ) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('cash_voucher_account_id')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_account_name')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_account_name" class="control-label"> Name : </label>
                                                        <input type="text" class="form-control" name="cash_voucher_account_name" id="cash_voucher_account_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6 {{ !empty($errors->first('cash_voucher_type')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_type_debit" class="control-label">Income : </label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="radio" name="cash_voucher_type" id="cash_voucher_type_debit" value="1" {{ empty(old('cash_voucher_type')) || old('cash_voucher_type') == '1' ? 'checked' : ''}}>
                                                            </span>
                                                            <label for="cash_voucher_type_debit" class="form-control">Debit</label>
                                                        </div>
                                                        @if(!empty($errors->first('cash_voucher_type')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_type')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 {{ !empty($errors->first('cash_voucher_type')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_type_debit" class="control-label">Expense : </label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="radio" name="cash_voucher_type" id="cash_voucher_type_credit" value="2" {{ old('cash_voucher_type') == '2' ? 'checked' : ''}}>
                                                            </span>
                                                            <label for="cash_voucher_type_credit" class="form-control">Credit</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_amount')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_amount" class="control-label">Amount : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="cash_voucher_amount" id="cash_voucher_amount" tabindex="4">
                                                        @if(!empty($errors->first('cash_voucher_amount')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_amount')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_description')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_description" class="control-label">Description : </label>
                                                        <input type="text" class="form-control" name="cash_voucher_description" id="cash_voucher_description" tabindex="5">
                                                        @if(!empty($errors->first('cash_voucher_description')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_description')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div><br>
                                                <div class="row">
                                                    <div class="col-xs-2"></div>
                                                    <div class="col-xs-4">
                                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="7">Clear</button>
                                                    </div>
                                                    {{-- <div class="col-sm-1"></div> --}}
                                                    <div class="col-xs-4">
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="6">Add</button>
                                                    </div>
                                                    <!-- /.col -->
                                                </div><br>
                                                <div class="box-header with-border"></div><br>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.form end -->
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h4>Last 5 cash voucher</h4>
                                        </div>
                                    </div>
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
                            <!-- /.tab-pane -->
                            <div class="{{ (old('tab_flag') == 'credit_voucher' || (!empty(Session::get('controller_tab_flag')) && Session::get('controller_tab_flag') == 'credit_voucher')) ? 'active' : '' }} tab-pane" id="credit_voucher_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('credit-voucher-register-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="credit_voucher">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_date')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_date" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="credit_voucher_date" id="credit_voucher_date" placeholder="Date" value="{{ old('credit_voucher_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('credit_voucher_date')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_time')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_time" class="control-label">Time : </label>
                                                        <div class="bootstrap-timepicker">
                                                            <input type="text" class="form-control timepicker" name="credit_voucher_time" id="credit_voucher_time" placeholder="Time" value="{{ old('credit_voucher_time') }}" tabindex="2">
                                                        </div>
                                                        @if(!empty($errors->first('credit_voucher_time')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_time')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_debit_account_id')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_debit_account_id" class="control-label">Debit Account : </label>
                                                        <select class="form-control account_select" name="credit_voucher_debit_account_id" id="credit_voucher_debit_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && count($accounts) > 0)
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ (old('credit_voucher_debit_account_id') == $account->id ) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('credit_voucher_debit_account_id')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_debit_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_debit_account_name')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_debit_account_name" class="control-label">Name : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_debit_account_name" id="credit_voucher_debit_account_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_credit_account_id')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_credit_account_id" class="control-label">Credit Account : </label>
                                                        <select class="form-control  account_select" name="credit_voucher_credit_account_id" id="credit_voucher_credit_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && count($accounts) > 0)
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ (old('credit_voucher_credit_account_id') == $account->id ) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('credit_voucher_credit_account_id')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_credit_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_credit_account_name')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_credit_account_name" class="control-label">Name : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_credit_account_name" id="credit_voucher_credit_account_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_amount')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_amount" class="control-label">Amount : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="credit_voucher_amount" id="credit_voucher_amount" tabindex="4">
                                                        @if(!empty($errors->first('credit_voucher_amount')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_amount')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('credit_voucher_description')) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_description" class="control-label">Description : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_description" id="credit_voucher_description" tabindex="5">
                                                        @if(!empty($errors->first('credit_voucher_description')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_description')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div><br>
                                                <div class="row">
                                                    <div class="col-xs-2"></div>
                                                    <div class="col-xs-4">
                                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="7">Clear</button>
                                                    </div>
                                                    {{-- <div class="col-sm-1"></div> --}}
                                                    <div class="col-xs-4">
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="6">Add</button>
                                                    </div>
                                                    <!-- /.col -->
                                                </div><br>
                                                <div class="box-header with-border"></div><br>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.form end -->
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h4>Last 5 credit vouchers</h4>
                                        </div>
                                    </div>
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
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/registration/voucherRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection