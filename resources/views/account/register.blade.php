@extends('layouts.app')
@section('title', 'Account Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Account</a></li>
            <li class="active">Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {{ Session::get('message') }}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="float: left;">Account Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('account-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <label for="account_name" class="col-sm-2 control-label"><b style="color: red;">* </b> Account Name : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('account_name')) ? 'has-error' : '' }}">
                                            <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name') }}"  tabindex="1">
                                            @if(!empty($errors->first('account_name')))
                                                <p style="color: red;" >{{$errors->first('account_name')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-sm-2 control-label">Description : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                            @if(!empty(old('description')))
                                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2">{{ old('description') }}</textarea>
                                            @else
                                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2"></textarea>
                                            @endif
                                            @if(!empty($errors->first('description')))
                                                <p style="color: red;" >{{$errors->first('description')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="account_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Account Type : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('account_type')) ? 'has-error' : '' }}">
                                            <select class="form-control" name="account_type" id="account_type" tabindex="3">
                                                <option value="" {{ empty(old('account_type')) ? 'selected' : '' }}>Select account type</option>
                                                <option value="real" {{ (old('account_type') == 'real') ? 'selected' : '' }}>Real account</option>
                                                <option value="nominal" {{ (old('account_type') == 'nominal') ? 'selected' : '' }}>Nominal account</option>
                                                <option value="personal" {{ (old('account_type') == 'personal') ? 'selected' : '' }}>Personal account</option>
                                            </select>
                                            @if(!empty($errors->first('account_type')))
                                                <p style="color: red;" >{{$errors->first('account_type')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="personal_account_details" {{ old('account_type') != 'personal' ? 'hidden' : '' }}>
                                        <br>
                                        <div class="box-header with-border">
                                            <h3 class="box-title" style="float: left;">Personal Details</h3>
                                                <p id="real_account_flag_message" style="color:blue;" {{ (old('account_type') != 'personal')  && !empty(old('account_type')) ? '' : 'hidden' }}>&nbsp&nbsp&nbsp Fields will be auto filled do not edit these fields for real accounts or nominal accounts.</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Name : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Account holder name" value="{{ old('name') }}" tabindex="5" {{ (old('account_type') != 'personal') && !empty(old('account_type')) ? 'disabled' : '' }}>
                                                @if(!empty($errors->first('name')))
                                                    <p style="color: red;" >{{$errors->first('name')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone" class="col-sm-2 control-label"> Phone : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('phone')) ? 'has-error' : '' }}">
                                                <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="6" {{ (old('account_type') != 'personal')  && !empty(old('account_type')) ? 'disabled' : '' }}>
                                                @if(!empty($errors->first('phone')))
                                                    <p style="color: red;" >{{$errors->first('phone')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-sm-2 control-label">Address : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('address')) ? 'has-error' : '' }}">
                                                @if(!empty(old('address')))
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="7" {{ (old('account_type') != 'personal')  && !empty(old('account_type')) ? 'disabled' : '' }}>{{ old('address') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="7" {{ (old('account_type') != 'personal')  && !empty(old('account_type')) ? 'disabled' : '' }}></textarea>
                                                @endif
                                                @if(!empty($errors->first('address')))
                                                    <p style="color: red;" >{{$errors->first('address')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label for="relation_type" class="col-sm-2 control-label">Primary Relation : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('relation_type')) ? 'has-error' : '' }}">
                                                <select class="form-control" name="relation_type" id="relation_type" tabindex="8" {{ (old('account_type') != 'personal')  && !empty(old('account_type')) ? 'disabled' : '' }}>
                                                    <option value="" {{ empty(old('relation_type')) ? 'selected' : '' }}>Select primary relation type</option>
                                                    <option value="employee" {{ (old('relation_type') == 'employee') ? 'selected' : '' }}>Employee</option>
                                                    <option value="supplier" {{ (old('relation_type') == 'supplier') ? 'selected' : '' }}>Supplier</option>
                                                    <option value="customer" {{ (old('relation_type') == 'customer') ? 'selected' : '' }}>Customer</option>
                                                    <option value="other" {{ (old('relation_type') == 'other') ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @if(!empty($errors->first('relation_type')))
                                                    <p style="color: red;" >{{$errors->first('relation_type')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="box-header with-border">
                                        <h3 class="box-title" style="float: left;">Financial Details</h3>
                                            <p>&nbsp&nbsp&nbsp</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="financial_status" class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('financial_status')) ? 'has-error' : '' }}">
                                            <select class="form-control" name="financial_status" id="financial_status"  tabindex="9">
                                                <option value="" {{ empty(old('financial_status')) ? 'selected' : '' }}>Select financial status</option>
                                                <option value="none" {{ (old('financial_status') == 'none') ? 'selected' : '' }}>None (No pending transactions)</option>
                                                <option value="credit" {{ (old('financial_status') == 'credit') ? 'selected' : '' }}>Debitor (Account holder owe company)</option>
                                                <option value="debit" {{ (old('financial_status') == 'debit') ? 'selected' : '' }}>Creditor (Company owe account holder)</option>
                                            </select>
                                            @if(!empty($errors->first('financial_status')))
                                                <p style="color: red;" >{{$errors->first('financial_status')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="opening_balance" class="col-sm-2 control-label"><b style="color: red;">* </b> Opening Balance : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('opening_balance')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ old('opening_balance') }}" ="" tabindex="10">
                                            @if(!empty($errors->first('opening_balance')))
                                                <p style="color: red;" >{{$errors->first('opening_balance')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="12">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="11">Submit</button>
                                </div>
                                <!-- /.col -->
                            </div><br>
                        </div>
                    </form>
                </div>
                <!-- /.box primary -->
            </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection