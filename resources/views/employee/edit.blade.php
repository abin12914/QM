@extends('layouts.app')
@section('title', 'Employee Updation')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Employee
            <small>Updation</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Employee Updation</li>
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="float: left;">Employee Updation</h3>
                        <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('employee-updation-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <input type="hidden" name="account_id" value="{{ $employee->account_id }}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ $employee->account->accountDetail->name }}" tabindex="1">
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-sm-2 control-label"><b style="color: red;">* </b> Phone : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('phone')) ? 'has-error' : '' }}">
                                        <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ !empty(old('phone')) ? old('phone') : $employee->account->accountDetail->phone }}" tabindex="2">
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
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3">{{ $employee->account->accountDetail->address }}</textarea>
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
                                            <option value="" {{ (empty(old('employee_type')) && empty($employee->employee_type)) ? 'selected' : '' }}>Select employee type</option>
                                            <option value="staff" {{ (old('employee_type') == 'staff' || $employee->employee_type == 'staff') ? 'selected' : '' }}>Staff</option>
                                            <option value="labour" {{ (old('employee_type') == 'labour' || $employee->employee_type == 'labour') ? 'selected' : '' }}>Labour</option>
                                        </select>
                                        @if(!empty($errors->first('employee_type')))
                                            <p style="color: red;" >{{$errors->first('employee_type')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" id="salary_div" {{ (old('employee_type') == 'labour' || $employee->employee_type == 'labour') ? 'hidden' : '' }}>
                                    <label for="salary" class="col-sm-2 control-label"><b style="color: red;">* </b> Monthly Salary : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('salary')) ? 'has-error' : '' }}">
                                        <input type="text" name="salary" class="form-control decimal_number_only" id="salary" placeholder="Monthly salary" value="{{ !empty(old('salary')) ? old('salary') : $employee->salary }}" tabindex="6" {{ (old('employee_type') == 'labour' || $employee->employee_type == 'labour') ? 'disabled' : '' }}>
                                        @if(!empty($errors->first('salary')))
                                            <p style="color: red;" >{{$errors->first('salary')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" id="daily_wage_div" {{ (old('employee_type') == 'staff' || $employee->employee_type == 'staff') ? 'hidden' : '' }}>
                                    <label for="wage" class="col-sm-2 control-label"><b style="color: red;">* </b> Daily Wage : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('wage')) ? 'has-error' : '' }}">
                                        <input type="text" name="wage" class="form-control decimal_number_only" id="wage" placeholder="Daily wage" value="{{ !empty(old('wage')) ? old('wage') : $employee->wage }}" tabindex="6" {{ (old('employee_type') == 'staff' || $employee->employee_type == 'staff') ? 'disabled' : '' }}>
                                        @if(!empty($errors->first('wage')))
                                            <p style="color: red;" >{{$errors->first('wage')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="account_name" class="col-sm-2 control-label"><b style="color: red;">* </b> Account Name : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('account_name')) ? 'has-error' : '' }}">
                                        <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ $employee->account->account_name }}"  disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('financial_status')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="financial_status" id="financial_status" disabled>
                                            <option value="" {{ empty($employee->account->financial_status) ? 'selected' : '' }}>Nil</option>
                                            <option value="none" {{ ($employee->account->financial_status == 'none') ? 'selected' : '' }}>None (No pending transactions)</option>
                                            <option value="credit" {{ ($employee->account->financial_status == 'credit') ? 'selected' : '' }}>Debitor (Account Holder Owe Company)</option>
                                            <option value="debit" {{ ($employee->account->financial_status == 'debit') ? 'selected' : '' }}>Creditor (Company Owe Account Holder)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Opening Balance: </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('opening_balance')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ $employee->account->opening_balance }}" disabled>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="11">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="10">Update</button>
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
@section('scripts')
    <script src="/js/registration/employeeRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection