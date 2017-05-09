@extends('layouts.app')
@section('title', 'labour-register-action Registration')
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
                    <form action="{{route('labour-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="{{ !empty($errors->first('name')) ? 'form-group has-error' : 'form-group' }}">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" required>
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="{{ !empty($errors->first('phone')) ? 'form-group has-error' : 'form-group' }}">
                                    <label for="phone" class="col-sm-2 control-label">Phone(if any) : </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number" value="{{ old('phone') }}" required>
                                        @if(!empty($errors->first('phone')))
                                            <p style="color: red;" >{{$errors->first('phone')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="{{ !empty($errors->first('address')) ? 'form-group has-error' : 'form-group' }}">
                                    <label for="address" class="col-sm-2 control-label">Address(if any) : </label>
                                    <div class="col-sm-10">
                                        @if(!empty(old('address')))
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;">
                                                {{ old('address') }}
                                            </textarea>
                                        @else
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;"></textarea>
                                        @endif
                                        @if(!empty($errors->first('address')))
                                            <p style="color: red;" >{{$errors->first('address')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Account Type : </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="financial_status" id="financial_status">
                                            <option value="" selected="">Select Type</option>
                                            <option value="general">General</option>
                                            <option value="customer">Customer</option>
                                            <option value="supplier">Supplier</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status : </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="financial_status" id="financial_status">
                                            <option value="" selected="">Select Status</option>
                                            <option value="none">None (No pending transactions)</option>
                                            <option value="debit">Debitor (Account Holder Owe Company)</option>
                                            <option value="credit">Creditor (Company Owe Account Holder)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Opening Balance : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="{{ old('opening_balance') }}">
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
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