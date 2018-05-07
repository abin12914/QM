@extends('layouts.app')
@section('title', 'Daily Resource List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Daily Resource
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Daily resource List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs no-print">
                            <li class="{{ Request::is('daily-statement/list/employee')? 'active' : '' }}"><a href="{{ Request::is('daily-statement/list/employee')? '#' : route('daily-statement-list-employee') }}">Employee Attendance List</a></li>
                            <li class="{{ Request::is('daily-statement/list/excavator')? 'active' : '' }}"><a href="{{ Request::is('daily-statement/list/excavator')? '#' : route('daily-statement-list-excavator') }}">Excavator Readings</a></li>
                            <li class="{{ Request::is('daily-statement/list/jackhammer')? 'active' : '' }}"><a href="{{ Request::is('daily-statement/list/jackhammer')? '#' : route('daily-statement-list-jackhammer') }}">Jack-Hammer Readings</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ Request::is('daily-statement/list/employee')? 'active' : '' }} tab-pane" id="employee_tab">
                                <!-- box-header -->
                                <div class="box-header no-print">
                                    <form action="{{ route('daily-statement-list-employee') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('attendance_employee_id')) ? 'has-error' : '' }}">
                                                        <label for="attendance_employee_id" class="control-label">Empoyee Name : </label>
                                                        <select class="form-control" name="attendance_employee_id" id="attendance_employee_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($employees) && (count($employees) > 0))
                                                                <option value="">Select employee</option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{ $employee->id }}" {{ ((old('attendance_employee_id') == $employee->id ) || $employeeId == $employee->id) ? 'selected' : '' }}>{{ $employee->account->accountDetail->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('attendance_employee_id')))
                                                            <p style="color: red;" >{{$errors->first('attendance_employee_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6     {{ !empty($errors->first('attendance_account_id')) ? 'has-error' : '' }}">
                                                        <label for="attendance_account_id" class="control-label">Employee Account : </label>
                                                        <select class="form-control" name="attendance_account_id" id="attendance_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && (count($accounts) > 0))
                                                                <option value="">Select employee account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ ((old('attendance_account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('attendance_account_id')))
                                                            <p style="color: red;" >{{$errors->first('attendance_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('attendance_from_date')) ? 'has-error' : '' }}">
                                                        <label for="attendance_from_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="attendance_from_date" id="attendance_from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('attendance_from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('attendance_from_date')))
                                                            <p style="color: red;" >{{$errors->first('attendance_from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('attendance_to_date')) ? 'has-error' : '' }}">
                                                        <label for="attendance_to_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="attendance_to_date" id="attendance_to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('attendance_to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('attendance_to_date')))
                                                            <p style="color: red;" >{{$errors->first('attendance_to_date')}}</p>
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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat  submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 20%;">Date</th>
                                                        <th style="width: 20%;">Employee Name</th>
                                                        <th style="width: 20%;">Account Name</th>
                                                        <th style="width: 20%;">Wage</th>
                                                        <th style="width: 15%;" class="no-print">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($employeeAttendance) && count($employeeAttendance) > 0)
                                                        @foreach($employeeAttendance as $index => $attendance)
                                                            <tr>
                                                                <td>{{ $index + $employeeAttendance->firstItem() }}</td>
                                                                <td>
                                                                    {{ Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}
                                                                    <i class="no-print"> / </i>
                                                                    <b class="no-print bg-info text-red">{{ $attendance->transaction->id }}</b>
                                                                </td>
                                                                <td>{{ $attendance->employee->account->accountDetail->name }}</td>
                                                                <td>{{ $attendance->employee->account->account_name }}</td>
                                                                <td>{{ $attendance->wage }}</td>
                                                                <td class="no-print">
                                                                    @if($attendance->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                        <form action="{{route('daily-statement-employee-attendance-delete-action')}}" id="employee_delete_{{ $attendance->id }}" method="post" style="float: left;">
                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                            <input type="hidden" name="attenddance_id" value="{{ $attendance->id }}">
                                                                            <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}">
                                                                            <button type="button" class="btn btn-danger employee_delete_button" data-employee-delete-id="{{ $attendance->id }}" data-employee-transaction-id="{{ $sale->transaction->id }}" type="button">
                                                                                <i class="fa fa-trash"> Delete</i>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                            <i class="fa fa-exclamation-circle"> No Access</i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if(Request::get('page') == $employeeAttendance->lastPage() || $employeeAttendance->lastPage() == 1)
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b>Total Amount</b></td>
                                                                <td></td>
                                                                <td><b>{{ $totalAmount }}</b></td>
                                                                <td class="no-print"></td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row no-print">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($employeeAttendance))
                                                        {{ $employeeAttendance->appends(Request::all())->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="modal modal modal-danger" id="employee_delete_confirmation_modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title">Confirm Action</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="modal_warning">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <b id="employee_modal_warning_record_id" class="pull-right"></b>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <p>
                                                            <b> Are you sure to delete this record?</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="delete_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                                            <button type="button" id="employee_delete_confirmation_modal_confirm" class="btn btn-primary" data-employee-delete-modal-id="0" data-dismiss="modal">Confirm</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                            <div class="{{ Request::is('daily-statement/list/excavator')? 'active' : '' }} tab-pane" id="excavators_tab">
                                <!-- box-header -->
                                <div class="box-header no-print">
                                    <form action="{{ route('daily-statement-list-excavator') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_id')) ? 'has-error' : '' }}">
                                                        <label for="excavator_id" class="control-label">Excavator : </label>
                                                        <select class="form-control" name="excavator_id" id="excavator_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($excavators) && (count($excavators) > 0))
                                                                <option value="">Select excavator</option>
                                                                @foreach($excavators as $excavator)
                                                                    <option value="{{ $excavator->id }}" {{ ((old('excavator_id') == $excavator->id ) || $excavatorId == $excavator->id) ? 'selected' : '' }}>{{ $excavator->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('excavator_id')))
                                                            <p style="color: red;" >{{$errors->first('excavator_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6     {{ !empty($errors->first('excavator_account_id')) ? 'has-error' : '' }}">
                                                        <label for="excavator_account_id" class="control-label">Contractor Account : </label>
                                                        <select class="form-control" name="excavator_account_id" id="excavator_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && (count($accounts) > 0))
                                                                <option value="">Select employee account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ ((old('excavator_account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('excavator_account_id')))
                                                            <p style="color: red;" >{{$errors->first('excavator_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_from_date')) ? 'has-error' : '' }}">
                                                        <label for="excavator_from_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="excavator_from_date" id="excavator_from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('excavator_from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('excavator_from_date')))
                                                            <p style="color: red;" >{{$errors->first('excavator_from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('excavator_to_date')) ? 'has-error' : '' }}">
                                                        <label for="excavator_to_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="excavator_to_date" id="excavator_to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('excavator_to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('excavator_to_date')))
                                                            <p style="color: red;" >{{$errors->first('excavator_to_date')}}</p>
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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 15%;">Date / Ref. No.</th>
                                                        <th style="width: 10%;">Excavator</th>
                                                        <th style="width: 15%;">Contractor Account</th>
                                                        <th style="width: 10%;">Bucket [Working Hour]</th>
                                                        <th style="width: 10%;">Breaker [Working Hour]</th>
                                                        <th style="width: 15%;">Operator</th>
                                                        <th style="width: 5%;">Operator Bata</th>
                                                        <th style="width: 5%;">Bill Amount</th>
                                                        <th style="width: 10%;" class="no-print">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($excavatorReadings as $index => $excavatorReading)
                                                        <tr>
                                                            <td>{{ $index + $excavatorReadings->firstItem() }}</td>
                                                            <td>
                                                                {{ Carbon\Carbon::parse($excavatorReading->date)->format('d-m-Y') }}
                                                                <i class="no-print"> / </i>
                                                                <b class="no-print bg-info text-red">{{ $excavatorReading->transaction->id }}</b>
                                                            </td>
                                                            <td>{{ $excavatorReading->excavator->name }}</td>
                                                            <td>{{ $excavatorReading->excavator->account->account_name }}</td>
                                                            <td>
                                                                {{ $excavatorReading->bucket_hour }}
                                                                @if($excavatorReading->bucket_hour > 0 && !empty($excavatorReading->operator_account_id))
                                                                     x {{ round(($excavatorReading->bill_amount/$excavatorReading->bucket_hour), 2) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $excavatorReading->breaker_hour }}
                                                                @if($excavatorReading->breaker_hour > 0 && !empty($excavatorReading->operator_account_id))
                                                                     x {{ round(($excavatorReading->bill_amount/$excavatorReading->breaker_hour), 2) }}
                                                                @endif
                                                            </td>
                                                            @if(!empty($excavatorReading->operator_account_id))
                                                                <td>{{ $excavatorReading->operator->account_name }}</td>
                                                            @else
                                                                <td>{{ $excavatorReading->operator_name }}</td>
                                                            @endif
                                                            <td>{{ $excavatorReading->bata }}</td>
                                                            <td>{{ $excavatorReading->bill_amount }}</td>
                                                            <td class="no-print">
                                                                @if($excavatorReading->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                    <form action="{{route('daily-statement-excavator-readings-delete-action')}}" id="excavator_delete_{{ $excavatorReading->id }}" method="post" style="float: left;">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <input type="hidden" name="excavator_id" value="{{ $excavatorReading->id }}">
                                                                        <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($excavatorReading->date)->format('d-m-Y') }}">
                                                                        <button type="button" class="btn btn-danger excavator_delete_button" data-excavator-delete-id="{{ $excavatorReading->id }}" type="button">
                                                                            <i class="fa fa-trash"> Delete</i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                        <i class="fa fa-exclamation-circle"> No Access</i>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @if(!empty($excavatorReadings) && (Request::get('page') == $excavatorReadings->lastPage() || $excavatorReadings->lastPage() == 1))
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="no-print"></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td><b>Total Amount</b></td>
                                                            <td></td>

                                                            <td><b>{{ $totalBucketReading }}</b></td>
                                                            <td><b>{{ $totalBreakerReading }}</b></td>
                                                            {{-- @if(!empty($bucketRate))
                                                                <td><b>{{ $totalBucketReading }} x {{ $bucketRate }} = {{ ($totalBucketReading * $bucketRate) }}</b></td>
                                                            @else
                                                                <td><b>{{ $totalBucketReading }}</b></td>
                                                            @endif
                                                            @if(!empty($breakerRate))
                                                                <td><b>{{ $totalBreakerReading }} x {{ $breakerRate }} = {{ ($totalBreakerReading * $breakerRate) }}</b></td>
                                                            @else
                                                                <td><b>{{ $totalBreakerReading }}</b></td>
                                                            @endif --}}
                                                            <td></td>
                                                            <td><b>{{ $totalBata }}</b></td>
                                                            <td><b>{{ $totalAmount }}</b></td>
                                                            <td class="no-print"></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row no-print">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($excavatorReadings))
                                                        {{ $excavatorReadings->appends(Request::all())->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="modal modal modal-danger" id="excavator_delete_confirmation_modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title">Confirm Action</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="modal_warning">
                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-10">
                                                        <p>
                                                            <b> Are you sure to delete this record?</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="delete_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                                            <button type="button" id="excavator_delete_confirmation_modal_confirm" class="btn btn-primary" data-excavator-delete-modal-id="" data-dismiss="modal">Confirm</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                            <div class="{{ Request::is('daily-statement/list/jackhammer')? 'active' : '' }} tab-pane" id="jack_hammers_tab">
                                <!-- box-header -->
                                <div class="box-header no-print">
                                    <form action="{{ route('daily-statement-list-jackhammer') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_id')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_id" class="control-label">Jackhammer : </label>
                                                        <select class="form-control" name="jackhammer_id" id="jackhammer_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($jackhammers) && (count($jackhammers) > 0))
                                                                <option value="">Select jackhammer</option>
                                                                @foreach($jackhammers as $jackhammer)
                                                                    <option value="{{ $jackhammer->id }}" {{ ((old('jackhammer_id') == $jackhammer->id ) || $jackhammerId == $jackhammer->id) ? 'selected' : '' }}>{{ $jackhammer->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('jackhammer_id')))
                                                            <p style="color: red;" >{{$errors->first('jackhammer_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6     {{ !empty($errors->first('jackhammer_account_id')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_account_id" class="control-label">Contractor Account : </label>
                                                        <select class="form-control" name="jackhammer_account_id" id="jackhammer_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && (count($accounts) > 0))
                                                                <option value="">Select employee account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ ((old('jackhammer_account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('jackhammer_account_id')))
                                                            <p style="color: red;" >{{$errors->first('jackhammer_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_from_date')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_from_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="jackhammer_from_date" id="jackhammer_from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('jackhammer_from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('jackhammer_from_date')))
                                                            <p style="color: red;" >{{$errors->first('jackhammer_from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_to_date')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_to_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="jackhammer_to_date" id="jackhammer_to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('jackhammer_to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('jackhammer_to_date')))
                                                            <p style="color: red;" >{{$errors->first('jackhammer_to_date')}}</p>
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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 15%;">Date / Ref. No.</th>
                                                        <th style="width: 15%;">Jackhammer</th>
                                                        <th style="width: 15%;">Contractor Account</th>
                                                        <th style="width: 10%;">No of Pit [5 feet depth]</th>
                                                        <th style="width: 10%;">Total Pit Depth</th>
                                                        <th style="width: 15%;">Bill Amount</th>
                                                        <th style="width: 15%;" class="no-print">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jackhammerReadings as $index => $jackhammerReading)
                                                        <tr>
                                                            <td>{{ $index + $jackhammerReadings->firstItem() }}</td>
                                                            <td>
                                                                {{ Carbon\Carbon::parse($jackhammerReading->date)->format('d-m-Y') }}
                                                                <i class="no-print"> / </i>
                                                                <b class="no-print bg-info text-red">{{ $jackhammerReading->transaction->id }}</b>
                                                            </td>
                                                            <td>{{ $jackhammerReading->jackhammer->name }}</td>
                                                            <td>{{ $jackhammerReading->jackhammer->account->account_name }}</td>
                                                            <td>{{ ($jackhammerReading->total_pit_depth / 5) }}</td>
                                                            <td>
                                                                {{ $jackhammerReading->total_pit_depth }} x {{ round(($jackhammerReading->bill_amount/$jackhammerReading->total_pit_depth), 2) }}
                                                            </td>
                                                            <td>{{ $jackhammerReading->bill_amount }}</td>
                                                            <td class="no-print">
                                                                @if($jackhammerReading->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                    <form action="{{route('daily-statement-jackhammer-readings-delete-action')}}" id="jackhammer_delete_{{ $jackhammerReading->id }}" method="post" style="float: left;">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <input type="hidden" name="jackhammer_id" value="{{ $jackhammerReading->id }}">
                                                                        <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($jackhammerReading->date)->format('d-m-Y') }}">
                                                                        <button type="button" class="btn btn-danger jackhammer_delete_button" data-jackhammer-delete-id="{{ $jackhammerReading->id }}" type="button">
                                                                            <i class="fa fa-trash"> Delete</i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                        <i class="fa fa-exclamation-circle"> No Access</i>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @if(!empty($jackhammerReadings) && (Request::get('page') == $jackhammerReadings->lastPage() || $jackhammerReadings->lastPage() == 1))
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="no-print"></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td><b>Total Amount</b></td>
                                                            <td></td>
                                                            <td></td>
                                                            @if(!empty($jackhammerRate))
                                                                <td><b>{{ $totalDepth }} x {{ $jackhammerRate }}</b></td>
                                                            @else
                                                                <td><b>{{ $totalDepth }}</b></td>
                                                            @endif
                                                            <td><b>{{ $totalAmount }}</b></td>
                                                            <td class="no-print"></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row no-print">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($jackhammerReadings))
                                                        {{ $jackhammerReadings->appends(Request::all())->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->

                            <div class="modal modal modal-danger" id="jackhammer_delete_confirmation_modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title">Confirm Action</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="modal_warning">
                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-10">
                                                        <p>
                                                            <b> Are you sure to delete this record?</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="delete_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                                            <button type="button" id="jackhammer_delete_confirmation_modal_confirm" class="btn btn-primary" data-jackhammer-delete-modal-id="" data-dismiss="modal">Confirm</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
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
    <script src="/js/list/dailyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection