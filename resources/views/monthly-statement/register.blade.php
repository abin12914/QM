@extends('layouts.app')
@section('title', 'Daily Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Monthly Statement
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Monthly statement</a></li>
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
                    <!-- nav-tabs-custom -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="{{ ((old('tab_flag') == 'employee') || (empty(Session::get('controller_tab_flag')) && empty(old('tab_flag'))) || (Session::get('controller_tab_flag') == 'employee')) ? 'active' : '' }}"><a href="#employee_tab" data-toggle="tab">Employee Salary</a></li>
                            <li class="{{ (old('tab_flag') == 'excavator' || (!empty(Session::get('controller_tab_flag')) && (Session::get('controller_tab_flag') == 'excavator'))) ? 'active' : '' }}"><a href="#excavators_tab" data-toggle="tab">Excavator Rent</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ ((old('tab_flag') == 'employee') || (empty(Session::get('controller_tab_flag')) && empty(old('tab_flag'))) || (Session::get('controller_tab_flag') == 'employee')) ? 'active' : '' }} tab-pane" id="employee_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('monthly-statement-employee-salary-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="employee">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('emp_salary_employee_id')) ? 'has-error' : '' }}">
                                                        <label for="emp_salary_employee_id" class="control-label">Employee Name : </label>
                                                        <select class="form-control" name="emp_salary_employee_id" id="emp_salary_employee_id" tabindex="2" style="width: 100%">
                                                            @if(count($employees))
                                                                <option value="">Select employee name</option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{ $employee->id }}" {{ (old('emp_salary_employee_id') == $employee->id ) ? 'selected' : '' }}>{{ $employee->account->accountDetail->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('emp_salary_employee_id')))
                                                            <p style="color: red;" >{{$errors->first('emp_salary_employee_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('emp_salary_account_id')) ? 'has-error' : '' }}">
                                                        <label for="emp_salary_account_id" class="control-label">Employee Account : </label>
                                                        <select class="form-control" name="emp_salary_account_id" id="emp_salary_account_id" tabindex="3" style="width: 100%">
                                                            @if(count($employeeAccounts) > 0)
                                                                <option value="">Select employee account</option>
                                                                @foreach($employeeAccounts as $employeeAccount)
                                                                    <option value="{{ $employeeAccount->id }}" {{ (old('emp_salary_account_id') == $employeeAccount->id ) ? 'selected' : '' }}>{{ $employeeAccount->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('emp_salary_account_id')))
                                                            <p style="color: red;" >{{$errors->first('emp_salary_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('emp_salary_start_date')) ? 'has-error' : '' }}">
                                                        <label for="emp_salary_start_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="emp_salary_start_date" id="emp_salary_start_date" placeholder="Date" value="{{ old('emp_salary_start_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('emp_salary_start_date')))
                                                            <p style="color: red;" >{{$errors->first('emp_salary_start_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('emp_salary_end_date')) ? 'has-error' : '' }}">
                                                        <label for="emp_salary_end_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="emp_salary_end_date" id="emp_salary_end_date" placeholder="Date" value="{{ old('emp_salary_end_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('emp_salary_end_date')))
                                                            <p style="color: red;" >{{$errors->first('emp_salary_end_date')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('emp_salary_salary')) ? 'has-error' : '' }}">
                                                        <label for="emp_salary_salary" class="control-label">Salary : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="emp_salary_salary" id="emp_salary_salary" value="{{ old('emp_salary_salary') }}" tabindex="3">
                                                        @if(!empty($errors->first('emp_salary_salary')))
                                                            <p style="color: red;" >{{$errors->first('emp_salary_salary')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('emp_salary_description')) ? 'has-error' : '' }}">
                                                        <label for="emp_salary_description" class="control-label">Description : </label>
                                                        <input type="text" class="form-control" name="emp_salary_description" id="emp_salary_description" value="{{ old('emp_salary_description') }}" tabindex="3">
                                                        @if(!empty($errors->first('emp_salary_description')))
                                                            <p style="color: red;" >{{$errors->first('emp_salary_description')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div><br>
                                                <div class="row">
                                                    <div class="col-xs-2"></div>
                                                    <div class="col-xs-4">
                                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="5">Clear</button>
                                                    </div>
                                                    {{-- <div class="col-sm-1"></div> --}}
                                                    <div class="col-xs-4">
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4">Add</button>
                                                    </div>
                                                    <!-- /.col -->
                                                </div><br>
                                                <div class="box-header with-border"></div><br>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.form end -->
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ (old('tab_flag') == 'excavator' || (!empty(Session::get('controller_tab_flag')) && Session::get('controller_tab_flag') == 'excavator')) ? 'active' : '' }} tab-pane" id="excavators_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('monthly-statement-excavator-rent-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="excavator">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_id')) ? 'has-error' : '' }}">
                                                        <label for="excavator_id" class="control-label">Excavator : </label>
                                                        <select class="form-control" name="excavator_id" id="excavator_id" tabindex="2" style="width: 100%">
                                                            @if(count($excavators) > 0)
                                                                <option value="">Select excavator</option>
                                                                @foreach($excavators as $excavator)
                                                                    <option value="{{ $excavator->id }}" {{ (old('excavator_id') == $excavator->id ) ? 'selected' : '' }}>{{ $excavator->name }} / Contractor : {{ $excavator->account->accountDetail->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('excavator_id')))
                                                            <p style="color: red;" >{{$errors->first('excavator_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_contractor_name')) ? 'has-error' : '' }}">
                                                        <label for="excavator_contractor_name" class="control-label">Contractor Name : </label>
                                                        <input type="text" class="form-control" name="excavator_contractor_name" id="excavator_contractor_name" tabindex="3" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_from_date')) ? 'has-error' : '' }}">
                                                        <label for="excavator_from_date" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="excavator_from_date" id="excavator_from_date" placeholder="Date" value="{{ old('excavator_from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('excavator_from_date')))
                                                            <p style="color: red;" >{{$errors->first('excavator_from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_to_date')) ? 'has-error' : '' }}">
                                                        <label for="excavator_to_date" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="excavator_to_date" id="excavator_to_date" placeholder="Date" value="{{ old('excavator_to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('excavator_to_date')))
                                                            <p style="color: red;" >{{$errors->first('excavator_to_date')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_rent')) ? 'has-error' : '' }}">
                                                        <label for="excavator_rent" class="control-label">Monthly Rent : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="excavator_rent" id="excavator_rent" value="{{ old("excavator_rent") }}" tabindex="3">
                                                        @if(!empty($errors->first('excavator_rent')))
                                                            <p style="color: red;" >{{$errors->first('excavator_rent')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_description')) ? 'has-error' : '' }}">
                                                        <label for="excavator_description" class="control-label">Description : </label>
                                                        <input type="text" class="form-control" name="excavator_description" id="excavator_description" value="{{ old('excavator_description') }}" tabindex="3">
                                                        @if(!empty($errors->first('excavator_description')))
                                                            <p style="color: red;" >{{$errors->first('excavator_description')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div><br>
                                                <div class="row">
                                                    <div class="col-xs-2"></div>
                                                    <div class="col-xs-4">
                                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="5">Clear</button>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4">Add</button>
                                                    </div>
                                                    <!-- /.col -->
                                                </div><br>
                                                <div class="box-header with-border"></div><br>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.form end -->
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
    <script src="/js/monthlyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection