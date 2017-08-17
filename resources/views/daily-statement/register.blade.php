@extends('layouts.app')
@section('title', 'Daily Resource Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Daily Resource
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Daily Resource Registration</li>
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
                            <li class="{{ ((old('tab_flag') == 'employee') || (empty(Session::get('controller_tab_flag')) && empty(old('tab_flag'))) || (Session::get('controller_tab_flag') == 'employee')) ? 'active' : '' }}"><a href="#employee_tab" data-toggle="tab">Employee Attendance</a></li>
                            <li class="{{ (old('tab_flag') == 'excavator' || (!empty(Session::get('controller_tab_flag')) && (Session::get('controller_tab_flag') == 'excavator'))) ? 'active' : '' }}"><a href="#excavators_tab" data-toggle="tab">Excavators</a></li>
                            <li class="{{ (old('tab_flag') == 'jackhammer' || (!empty(Session::get('controller_tab_flag')) && (Session::get('controller_tab_flag') == 'jackhammer'))) ? 'active' : '' }}"><a href="#jack_hammers_tab" data-toggle="tab">Jack-Hammers</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ ((old('tab_flag') == 'employee') || (empty(Session::get('controller_tab_flag')) && empty(old('tab_flag'))) || (Session::get('controller_tab_flag') == 'employee')) ? 'active' : '' }} tab-pane" id="employee_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('daily-statement-employee-attendance-action') }}" method="post" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="employee">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('attendance_date')) ? 'has-error' : '' }}">
                                                        <label for="attendance_date" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="attendance_date" id="attendance_date" placeholder="Date" value="{{ old('attendance_date') }}" tabindex="1">
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('attendance_employee_id')) ? 'has-error' : '' }}">
                                                        <label for="attendance_employee_id" class="control-label">Employee Name : </label>
                                                        <select class="form-control" name="attendance_employee_id" id="attendance_employee_id" tabindex="2" style="width: 100%">
                                                            @if(count($employees))
                                                                <option value="">Select employee name</option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{ $employee->id }}" {{ (old('attendance_employee_id') == $employee->id ) ? 'selected' : '' }}>{{ $employee->account->accountDetail->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('attendance_account_id')) ? 'has-error' : '' }}">
                                                        <label for="attendance_account_id" class="control-label">Employee Account : </label>
                                                        <select class="form-control" name="attendance_account_id" id="attendance_account_id" tabindex="3" style="width: 100%">
                                                            @if(count($employeeAccounts) > 0)
                                                                <option value="">Select employee account</option>
                                                                @foreach($employeeAccounts as $employeeAccount)
                                                                    <option value="{{ $employeeAccount->id }}" {{ (old('attendance_account_id') == $employeeAccount->id ) ? 'selected' : '' }}>{{ $employeeAccount->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('attendance_wage')) ? 'has-error' : '' }}">
                                                        <label for="attendance_wage" class="control-label">Wage : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="attendance_wage" id="attendance_wage" value="{{ old('attendance_wage') }}" tabindex="3">
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
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h4>Attendance list : {{ $today->format('d-m-Y') }}</h4>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Account Name</th>
                                                <th>Wage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($employeeAttendance) > 0)
                                                @foreach($employeeAttendance as $index => $attendance)
                                                    <tr>
                                                        <td>{{ $index+1 }}</td>
                                                        <td>{{ $attendance->employee->account->accountDetail->name }}</td>
                                                        <td>{{ $attendance->employee->account->account_name }}</td>
                                                        <td>{{ $attendance->wage }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ (old('tab_flag') == 'excavator' || (!empty(Session::get('controller_tab_flag')) && Session::get('controller_tab_flag') == 'excavator')) ? 'active' : '' }} tab-pane" id="excavators_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('daily-statement-excavator-readings-action') }}" method="post" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="excavator">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_date')) ? 'has-error' : '' }}">
                                                        <label for="excavator_date" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="excavator_date" id="excavator_date" placeholder="Date" value="{{ old('excavator_date') }}" tabindex="1">
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_id')) ? 'has-error' : '' }}">
                                                        <label for="excavator_id" class="control-label">Excavator : </label>
                                                        <select class="form-control" name="excavator_id" id="excavator_id" tabindex="2" style="width: 100%">
                                                            @if(count($excavators))
                                                                <option value="">Select excavator</option>
                                                                @foreach($excavators as $excavator)
                                                                    <option value="{{ $excavator->id }}" {{ (old('excavator_id') == $excavator->id ) ? 'selected' : '' }}>{{ $excavator->name }} / Contractor : {{ $excavator->account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_contractor_name')) ? 'has-error' : '' }}">
                                                        <label for="excavator_contractor_name" class="control-label">Contractor Name : </label>
                                                        <input type="text" class="form-control" name="excavator_contractor_name" id="excavator_contractor_name" tabindex="3" readonly>
                                                    </div>
                                                </div> --}}
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_bucket_hour')) ? 'has-error' : '' }}">
                                                        <label for="excavator_bucket_hour" class="control-label">Bucket [Working Hour] : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="excavator_bucket_hour" id="excavator_bucket_hour" value="{{ old("excavator_bucket_hour") }}" tabindex="3">
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_breaker_hour')) ? 'has-error' : '' }}">
                                                        <label for="excavator_breaker_hour" class="control-label">Breaker [Working Hour] : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="excavator_breaker_hour" id="excavator_breaker_hour" value="{{ old("excavator_breaker_hour") }}" tabindex="3">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_operator')) ? 'has-error' : '' }}">
                                                        <label for="excavator_operator" class="control-label">Operator Name : </label>
                                                        <input type="text" class="form-control" name="excavator_operator" id="excavator_operator" value="{{ old("excavator_operator") }}" tabindex="3">
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_operator_bata')) ? 'has-error' : '' }}">
                                                        <label for="excavator_operator_bata" class="control-label">Operator Bata : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="excavator_operator_bata" id="excavator_operator_bata" value="{{ old("excavator_operator_bata") }}" tabindex="3">
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
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h4>Excavator Readings : {{ $today->format('d-m-Y') }}</h4>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Excavator</th>
                                                <th>Contractor Account</th>
                                                <th>Bucket [Working Hour]</th>
                                                <th>Breaker [Working Hour]</th>
                                                <th>Operator Bata</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($excavatorReadings as $index => $excavatorReading)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $excavatorReading->excavator->name }}</td>
                                                    <td>{{ $excavatorReading->excavator->account->account_name }}</td>
                                                    <td>{{ $excavatorReading->bucket_hour }}</td>
                                                    <td>{{ $excavatorReading->breaker_hour }}</td>
                                                    <td>{{ $excavatorReading->bata }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ (old('tab_flag') == 'jackhammer' || (!empty(Session::get('controller_tab_flag')) && Session::get('controller_tab_flag') == 'jackhammer')) ? 'active' : '' }} tab-pane" id="jack_hammers_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('daily-statement-jackhammer-readings-action') }}" method="post" class="form-horizontal">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="tab_flag" value="jackhammer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_date')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_date" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="jackhammer_date" id="jackhammer_date" placeholder="Date" value="{{ old('jackhammer_date') }}" tabindex="1">
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_id')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_id" class="control-label">JackHammer : </label>
                                                        <select class="form-control" name="jackhammer_id" id="jackhammer_id" tabindex="2" style="width: 100%">
                                                            @if(count($jackhammers))
                                                                <option value="">Select jackhammer</option>
                                                                @foreach($jackhammers as $jackhammer)
                                                                    <option value="{{ $jackhammer->id }}" {{ (old('jackhammer_id') == $jackhammer->id ) ? 'selected' : '' }}>{{ $jackhammer->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_contractor_account')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_contractor_account" class="control-label">Contractor : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="jackhammer_contractor_account" id="jackhammer_contractor_account" tabindex="3" readonly>
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_depth_per_pit')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_depth_per_pit" class="control-label">Depth Per Pit : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="jackhammer_depth_per_pit" id="jackhammer_depth_per_pit" value="{{ !empty(old('jackhammer_depth_per_pit')) ? old('jackhammer_depth_per_pit') : 5 }}" tabindex="3">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_no_of_pit')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_no_of_pit" class="control-label">No Of Pits : </label>
                                                        <input type="text" class="form-control number_only" name="jackhammer_no_of_pit" id="jackhammer_no_of_pit" tabindex="3">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="jackhammer_total_pit_depth" class="control-label">Total Pit Depth : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="jackhammer_total_pit_depth" id="jackhammer_total_pit_depth" tabindex="3" readonly>
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
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h4>Jackhammer Reading : {{ $today->format('d-m-Y') }}</h4>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jackhammer</th>
                                                <th>Contractor Account</th>
                                                <th>No of Pit [5 feet depth]</th>
                                                <th>Total Pit Depth</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jackhammerReadings as $index => $jackhammerReading)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $jackhammerReading->jackhammer->name }}</td>
                                                    <td>{{ $jackhammerReading->jackhammer->account->account_name }}</td>
                                                    <td>{{ ($jackhammerReading->total_pit_depth / 5) }}</td>
                                                    <td>{{ $jackhammerReading->total_pit_depth }}</td>
                                                </tr>
                                            @endforeach
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
    <script src="/js/registration/dailyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection