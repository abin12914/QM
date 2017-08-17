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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Employee Name</th>
                                                        <th>Account Name</th>
                                                        <th>Wage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($employeeAttendance) > 0)
                                                        @foreach($employeeAttendance as $index => $attendance)
                                                            <tr>
                                                                <td>{{ $index + $employeeAttendance->firstItem() }}</td>
                                                                <td>{{ $attendance->date }}</td>
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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                        <th>#</th>
                                                        <th>Date</th>
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
                                                            <td>{{ $index + $excavatorReadings->firstItem() }}</td>
                                                            <td>{{ $excavatorReading->date }}</td>
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
                            <div class="{{ Request::is('daily-statement/list/jackhammer')? 'active' : '' }} tab-pane" id="jack_hammers_tab">
                                <!-- box-header -->
                                <div class="box-header no-print">
                                    <form action="{{ route('daily-statement-list-jackhammer') }}" method="get" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('jackhammer_id')) ? 'has-error' : '' }}">
                                                        <label for="jackhammer_id" class="control-label">Excavator : </label>
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
                                                <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Jackhammer</th>
                                                        <th>Contractor Account</th>
                                                        <th>No of Pit [5 feet depth]</th>
                                                        <th>Total Pit Depth</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jackhammerReadings as $index => $jackhammerReading)
                                                        <tr>
                                                            <td>{{ $index + $jackhammerReadings->firstItem() }}</td>
                                                            <td>{{ $jackhammerReading->date }}</td>
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