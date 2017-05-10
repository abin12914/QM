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
                                    <div class="{{ !empty($errors->first('name')) ? 'form-group has-error' : 'form-group' }}">
                                        <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}"  tabindex="1">
                                            @if(!empty($errors->first('name')))
                                                <p style="color: red;" >{{$errors->first('name')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="{{ !empty($errors->first('phone')) ? 'form-group has-error' : 'form-group' }}">
                                        <label for="phone" class="col-sm-2 control-label">Phone(if any) : </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="2">
                                            @if(!empty($errors->first('phone')))
                                                <p style="color: red;" >{{$errors->first('phone')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="{{ !empty($errors->first('address')) ? 'form-group has-error' : 'form-group' }}">
                                        <label for="address" class="col-sm-2 control-label">Address(if any) : </label>
                                        <div class="col-sm-10">
                                            @if(!empty(old('address')))
                                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3">
                                                    {{ old('address') }}
                                                </textarea>
                                            @else
                                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3"></textarea>
                                            @endif
                                            @if(!empty($errors->first('address')))
                                                <p style="color: red;" >{{$errors->first('address')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="account_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Account Type : </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="account_type" id="account_type" required="" tabindex="4">
                                                <option value="" {{ empty(old('account_type')) ? 'selected' : '' }}>Select account type</option>
                                                <option value="general" {{ (old('account_type') == 'general') ? 'selected' : '' }}>General</option>
                                                <option value="customer" {{ (old('account_type') == 'customer') ? 'selected' : '' }}>Customer</option>
                                                <option value="supplier" {{ (old('account_type') == 'supplier') ? 'selected' : '' }}>Supplier</option>
                                            </select>
                                            @if(!empty($errors->first('account_type')))
                                                <p style="color: red;" >{{$errors->first('account_type')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="financial_status" class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status : </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="financial_status" id="financial_status" required tabindex="5">
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
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ old('opening_balance') }}" required="" tabindex="6">
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
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="7">Submit</button>
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