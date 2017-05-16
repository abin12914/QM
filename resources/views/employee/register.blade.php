@extends('layouts.app')
@section('title', 'Employee Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Employee
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Employee</a></li>
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
                        <h3 class="box-title" style="float: left;">Employee Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('employee-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" tabindex="1">
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-sm-2 control-label"><b style="color: red;">* </b> Phone : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('phone')) ? 'has-error' : '' }}">
                                        <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="2">
                                        @if(!empty($errors->first('phone')))
                                            <p style="color: red;" >{{$errors->first('phone')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-sm-2 control-label">Address : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('address')) ? 'has-error' : '' }}">
                                        @if(!empty(old('address')))
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3">{{ old('address') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3"></textarea>
                                        @endif
                                        @if(!empty($errors->first('address')))
                                            <p style="color: red;" >{{$errors->first('address')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image_file" class="col-sm-2 control-label">Image : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('image_file')) ? 'has-error' : '' }}">
                                        <input type="file" name="image_file" class="form-control" id="image_file" accept="image/*" tabindex="4">
                                        @if(!empty($errors->first('image_file')))
                                            <p style="color: red;" >{{$errors->first('image_file')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b>Employee Type : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('employee_type')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="employee_type" id="employee_type" tabindex="5">
                                            <option value="" {{ empty(old('employee_type')) ? 'selected' : '' }}>Select employee type</option>
                                            <option value="staff" {{ old('employee_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                                            <option value="labour" {{ old('employee_type') == 'labour' ? 'selected' : '' }}>Labour</option>
                                        </select>
                                        @if(!empty($errors->first('employee_type')))
                                            <p style="color: red;" >{{$errors->first('employee_type')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" id="salary_div" {{ !empty($errors->first('wage')) ? 'hidden' : '' }}>
                                    <label for="salary" class="col-sm-2 control-label"><b style="color: red;">* </b> Monthly Salary : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('salary')) ? 'has-error' : '' }}">
                                        <input type="text" name="salary" class="form-control decimal_number_only" id="salary" placeholder="Monthly salary" value="{{ old('salary') }}" tabindex="6" {{ !empty($errors->first('wage')) ? 'disabled' : '' }}>
                                        @if(!empty($errors->first('salary')))
                                            <p style="color: red;" >{{$errors->first('salary')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" id="daily_wage_div" {{ empty($errors->first('wage')) ? 'hidden' : '' }}>
                                    <label for="wage" class="col-sm-2 control-label"><b style="color: red;">* </b> Daily Wage : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('wage')) ? 'has-error' : '' }}">
                                        <input type="text" name="wage" class="form-control decimal_number_only" id="wage" placeholder="Daily wage" value="{{ old('wage') }}" tabindex="6" {{ empty($errors->first('wage')) ? 'disabled' : '' }}>
                                        @if(!empty($errors->first('wage')))
                                            <p style="color: red;" >{{$errors->first('wage')}}</p>
                                        @endif
                                    </div>
                                </div>
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
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('financial_status')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="financial_status" id="financial_status" tabindex="7">
                                            <option value="" {{ empty(old('financial_status')) ? 'selected' : '' }}>Select Status</option>
                                            <option value="none" {{ old('financial_status') == 'none' ? 'selected' : '' }}>None (No pending transactions)</option>
                                            <option value="debit" {{ old('financial_status') == 'debit' ? 'selected' : '' }}>Debitor (Account Holder Owe Company)</option>
                                            <option value="credit" {{ old('financial_status') == 'credit' ? 'selected' : '' }}>Creditor (Company Owe Account Holder)</option>
                                        </select>
                                        @if(!empty($errors->first('financial_status')))
                                            <p style="color: red;" >{{$errors->first('financial_status')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Opening Balance: </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('opening_balance')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ old('opening_balance') }}" tabindex="8">
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
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="10">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="9">Submit</button>
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