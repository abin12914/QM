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
                            <li class="{{ (old('tab_flag') == 'machine_voucher' || (!empty(Session::get('controller_tab_flag')) && (Session::get('controller_tab_flag') == 'machine_voucher'))) ? 'active' : '' }}"><a href="#machine_voucher_tab" data-toggle="tab">Voucher Through Machines</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ (old('tab_flag') == 'cash_voucher') || (empty(Session::get('controller_tab_flag')) && empty(old('tab_flag'))) || (Session::get('controller_tab_flag') == 'cash_voucher') ? 'active' : '' }} tab-pane" id="cash_voucher_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('cash-voucher-register-action') }}" method="post" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="cash_voucher">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_date')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_date" class="control-label"><b style="color: red;">*</b> Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="cash_voucher_date" id="cash_voucher_date" placeholder="Date" value="{{ old('cash_voucher_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('cash_voucher_date')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_time')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_time" class="control-label"><b style="color: red;">*</b> Time : </label>
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
                                                        <label for="cash_voucher_account_id" class="control-label"><b style="color: red;">*</b> Account : </label>
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
                                                        <label for="cash_voucher_amount" class="control-label"><b style="color: red;">*</b> Amount : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="cash_voucher_amount" id="cash_voucher_amount" tabindex="4">
                                                        @if(!empty($errors->first('cash_voucher_amount')))
                                                            <p style="color: red;" >{{$errors->first('cash_voucher_amount')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('cash_voucher_description')) ? 'has-error' : '' }}">
                                                        <label for="cash_voucher_description" class="control-label"><b style="color: red;">*</b> Description : </label>
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
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="6">Add</button>
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
                                                        <td>{{ Carbon\Carbon::parse($cashVoucher->date_time)->format('d-m-Y H:m:i') }}</td>
                                                        {{-- <td>{{ $cashVoucher->transaction->creditAccount->account_name }}</td>
                                                        <td>{{ $cashVoucher->transaction->creditAccount->accountDetail->name }}</td> --}}
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
                            <!-- /.tab-pane -->
                            <div class="{{ (old('tab_flag') == 'credit_voucher' || (!empty(Session::get('controller_tab_flag')) && Session::get('controller_tab_flag') == 'credit_voucher')) ? 'active' : '' }} tab-pane" id="credit_voucher_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('credit-voucher-register-action') }}" method="post" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="credit_voucher">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_date')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_date" class="control-label"><b style="color: red;">*</b> Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="credit_voucher_date" id="credit_voucher_date" placeholder="Date" value="{{ old('credit_voucher_date') }}" tabindex="1">
                                                        @if((!empty($errors->first('credit_voucher_date'))) && empty(old('machine_voucher_flag')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_time')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_time" class="control-label"><b style="color: red;">*</b> Time : </label>
                                                        <div class="bootstrap-timepicker">
                                                            <input type="text" class="form-control timepicker" name="credit_voucher_time" id="credit_voucher_time" placeholder="Time" value="{{ old('credit_voucher_time') }}" tabindex="2">
                                                        </div>
                                                        @if((!empty($errors->first('credit_voucher_time'))) && empty(old('machine_voucher_flag')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_time')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_debit_account_id')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_debit_account_id" class="control-label"><b style="color: red;">*</b> Debit Account : </label>
                                                        <select class="form-control account_select" name="credit_voucher_debit_account_id" id="credit_voucher_debit_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && count($accounts) > 0)
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ (old('credit_voucher_debit_account_id') == $account->id ) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if((!empty($errors->first('credit_voucher_debit_account_id'))) && empty(old('machine_voucher_flag')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_debit_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_debit_account_name')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_debit_account_name" class="control-label">Name : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_debit_account_name" id="credit_voucher_debit_account_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_credit_account_id')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_credit_account_id" class="control-label"><b style="color: red;">*</b> Credit Account : </label>
                                                        <select class="form-control  account_select" name="credit_voucher_credit_account_id" id="credit_voucher_credit_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && count($accounts) > 0)
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ (old('credit_voucher_credit_account_id') == $account->id ) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if((!empty($errors->first('credit_voucher_credit_account_id'))) && empty(old('machine_voucher_flag')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_credit_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_credit_account_name')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_credit_account_name" class="control-label">Name : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_credit_account_name" id="credit_voucher_credit_account_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_amount')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_amount" class="control-label"><b style="color: red;">*</b> Amount : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="credit_voucher_amount" id="credit_voucher_amount" tabindex="4">
                                                        @if((!empty($errors->first('credit_voucher_amount'))) && empty(old('machine_voucher_flag')))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_amount')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_description')) && empty(old('machine_voucher_flag'))) ? 'has-error' : '' }}">
                                                        <label for="credit_voucher_description" class="control-label"><b style="color: red;">*</b> Description : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_description" id="credit_voucher_description" tabindex="5">
                                                        @if((!empty($errors->first('credit_voucher_description'))) && empty(old('machine_voucher_flag')))
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
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="6">Add</button>
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
                                                    <td>{{ Carbon\Carbon::parse($creditVoucher->date_time)->format('d-m-Y H:m:i') }}</td>
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
                            <div class="{{ (old('tab_flag') == 'machine_voucher' || (!empty(Session::get('controller_tab_flag')) && Session::get('controller_tab_flag') == 'machine_voucher')) ? 'active' : '' }} tab-pane" id="machine_voucher_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('credit-voucher-register-action') }}" method="post" id="machine_voucher_form" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="machine_voucher">
                                        <input type="hidden" name="machine_voucher_flag" value="1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_date')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_date" class="control-label"><b style="color: red;">*</b> Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="credit_voucher_date" id="machine_voucher_date" placeholder="Date" value="{{ old('credit_voucher_date') }}" tabindex="1">
                                                        @if((!empty($errors->first('credit_voucher_date'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_time')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_time" class="control-label"><b style="color: red;">*</b> Time : </label>
                                                        <div class="bootstrap-timepicker">
                                                            <input type="text" class="form-control timepicker" name="credit_voucher_time" id="machine_voucher_time" placeholder="Time" value="{{ old('credit_voucher_time') }}" tabindex="2">
                                                        </div>
                                                        @if((!empty($errors->first('credit_voucher_time'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_time')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_debit_account_id')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_debit_account_id" class="control-label"><b style="color: red;">*</b> Debit Account : </label>
                                                        <select class="form-control account_select" name="credit_voucher_debit_account_id" id="machine_voucher_debit_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && count($accounts) > 0)
                                                                <option value="">Select account</option>
                                                                <option value="1">Cash account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ (old('credit_voucher_debit_account_id') == $account->id ) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if((!empty($errors->first('credit_voucher_debit_account_id'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_debit_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_debit_account_name')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_debit_account_name" class="control-label">Name : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_debit_account_name" id="machine_voucher_debit_account_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_machine_class')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_machine_class" class="control-label"><b style="color: red;">*</b> Machine Type : </label>
                                                        <select class="form-control" name="credit_voucher_machine_class" id="machine_voucher_machine_class" tabindex="3" style="width: 100%">
                                                            <option value="1" {{ (old('credit_voucher_machine_class') == 1) ? 'selected' : '' }}>Excavator</option>
                                                            <option value="2" {{ (old('credit_voucher_machine_class') == 2) ? 'selected' : '' }}>Jackhammer</option>
                                                        </select>
                                                        @if((!empty($errors->first('credit_voucher_machine_class'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_machine_class')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('machine_voucher_excavator_id')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}" id="class_excavator" {{ (!empty(old('machine_voucher_machine_class')) && old('machine_voucher_machine_class') == 2) ? 'hidden' : '' }}>
                                                        <label for="machine_voucher_excavator_id" class="control-label"><b style="color: red;">*</b> Excavator : </label>
                                                        <select class="form-control machine_voucher_machine_id" name="machine_voucher_excavator_id" id="machine_voucher_excavator_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($excavators) && count($excavators) > 0)
                                                                <option value="">Select excavator</option>
                                                                @foreach($excavators as $excavator)
                                                                    <option value="{{ $excavator->id }}" {{ (old('machine_voucher_excavator_id') == $excavator->id ) ? 'selected' : '' }} data-excavator-contractor-account-id="{{ $excavator->account->id }}">{{ $excavator->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if((!empty($errors->first('machine_voucher_excavator_id'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('machine_voucher_excavator_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('machine_voucher_jackhammer_id')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}" id="class_jackhammer" {{ (empty(old('machine_voucher_machine_class')) || old('machine_voucher_machine_class') == 1) ? 'hidden' : '' }}>
                                                        <label for="machine_voucher_jackhammer_id" class="control-label"><b style="color: red;">*</b> Jackhmmer : </label>
                                                        <select class="form-control machine_voucher_machine_id" name="machine_voucher_jackhammer_id" id="machine_voucher_jackhammer_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($jackhammers) && count($jackhammers) > 0)
                                                                <option value="">Select jackhammer</option>
                                                                @foreach($jackhammers as $jackhammer)
                                                                    <option value="{{ $jackhammer->id }}" {{ (old('machine_voucher_jackhammer_id') == $jackhammer->id ) ? 'selected' : '' }} data-jackhammer-contractor-account-id="{{ $jackhammer->account->id }}">{{ $jackhammer->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if((!empty($errors->first('machine_voucher_jackhammer_id'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('machine_voucher_jackhammer_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_credit_account_id')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_credit_account_id" class="control-label"><b style="color: red;">*</b> Credit Account : </label>
                                                        <select class="form-control  account_select" name="credit_voucher_credit_account_id" id="machine_voucher_credit_account_id" tabindex="3" style="width: 100%" disabled>
                                                            @if(!empty($accounts) && count($accounts) > 0)
                                                                <option value="">Select account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ (old('credit_voucher_credit_account_id') == $account->id ) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if((!empty($errors->first('credit_voucher_credit_account_id'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_credit_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_credit_account_name')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_credit_account_name" class="control-label">Name : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_credit_account_name" id="machine_voucher_credit_account_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_amount')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_amount" class="control-label"><b style="color: red;">*</b> Amount : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="credit_voucher_amount" id="machine_voucher_amount" tabindex="4">
                                                        @if((!empty($errors->first('credit_voucher_amount'))) && (old('machine_voucher_flag') == 1))
                                                            <p style="color: red;" >{{$errors->first('credit_voucher_amount')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ (!empty($errors->first('credit_voucher_description')) && old('machine_voucher_flag') == 1) ? 'has-error' : '' }}">
                                                        <label for="machine_voucher_description" class="control-label"><b style="color: red;">*</b> Description : </label>
                                                        <input type="text" class="form-control" name="credit_voucher_description" id="machine_voucher_description" tabindex="5">
                                                        @if((!empty($errors->first('credit_voucher_description'))) && (old('machine_voucher_flag') == 1))
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
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="6">Add</button>
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
                                                <th>Description</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($machineVouchers) && count($machineVouchers) > 0)
                                            @foreach($machineVouchers as $index => $machineVoucher)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ Carbon\Carbon::parse($machineVoucher->date_time)->format('d-m-Y H:m:i') }}</td>
                                                    <td>{{ $machineVoucher->transaction->creditAccount->account_name }}</td>
                                                    <td>{{ $machineVoucher->transaction->debitAccount->account_name }}</td>
                                                    <td>{{ $machineVoucher->transaction->particulars }}</td>
                                                    <td>{{ $machineVoucher->amount }}</td>
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