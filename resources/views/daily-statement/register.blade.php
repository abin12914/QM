@extends('layouts.app')
@section('title', 'Daily Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Daily Statement
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Daily statement</a></li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <!-- nav-tabs-custom -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#employee_tab" data-toggle="tab">Employee Attendance</a></li>
                            <li><a href="#excavators_tab" data-toggle="tab">Excavators</a></li>
                            <li><a href="#jack_hammers_tab" data-toggle="tab">Jack-Hammers</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="employee_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('daily-statement-employee-attendance-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                        <label for="date_credit" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date" placeholder="Date" value="{{ old('date') }}" tabindex="1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="date_credit" class="control-label">Employee Name : </label>
                                                        <select class="form-control account" name="account_id" id="attendance_account_id" tabindex="2" style="width: 100%">
                                                            <option value="">Select employee name</option>
                                                            @foreach($employeeAccounts as $employeeAccount)
                                                                <option value="{{ $employeeAccount->id }}">{{ $employeeAccount->account_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                                        <label for="date_credit" class="control-label">Employee Account Name : </label>
                                                        <select class="form-control account" name="account_id" id="attendance_account_id" tabindex="3" style="width: 100%">
                                                            <option value="">Select employee account</option>
                                                            @foreach($employeeAccounts as $employeeAccount)
                                                                <option value="{{ $employeeAccount->id }}">{{ $employeeAccount->account_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('wage')) ? 'has-error' : '' }}">
                                                        <label for="date_credit" class="control-label">Wage : </label>
                                                        <input type="text" class="form-control" name="wage" id="wage" value="" tabindex="3">
                                                    </div>
                                                </div>
                                                <div class="form-group" hidden>
                                                    <div class="col-sm-6"></div>
                                                    <div class="col-sm-6"></div>
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
                                            @foreach($employeeAttendance as $index => $attendance)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $attendance->employee->account->accountDetail->name }}</td>
                                                    <td>{{ $attendance->employee->account->account_name }}</td>
                                                    <td>{{ $attendance->wage }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="excavators_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('daily-statement-employee-attendance-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                        <label for="date_credit" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date" placeholder="Date" value="{{ old('date') }}" tabindex="1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="date_credit" class="control-label">Exavator : </label>
                                                        <select class="form-control account" name="account_id" id="attendance_account_id" tabindex="2" style="width: 100%">
                                                            <option value="">Select excavator</option>
                                                            @foreach($employeeAccounts as $employeeAccount)
                                                                <option value="{{ $employeeAccount->id }}">{{ $employeeAccount->account_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                                        <label for="bucket_reading" class="control-label">Bucket Running Hour: </label>
                                                        <input type="text" class="form-control" name="bucket_reading" id="bucket_reading" value="" tabindex="3">
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('wage')) ? 'has-error' : '' }}">
                                                        <label for="date_credit" class="control-label">Breaker Running Hour : </label>
                                                        <input type="text" class="form-control" name="breaker_reading" id="breaker_reading" value="" tabindex="3">
                                                    </div>
                                                </div>
                                                <div class="form-group" hidden>
                                                    <div class="col-sm-6"></div>
                                                    <div class="col-sm-6"></div>
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
                                            <h4>Excavator working list : {{ $today->format('d-m-Y') }}</h4>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Excavator</th>
                                                <th>Contractor</th>
                                                <th>Bucket Reading</th>
                                                <th>Breaker Reading</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employeeAttendance as $index => $attendance)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="jack_hammers_tab">
                                <div class="box-body">
                                    <!-- form start -->
                                    <form action="{{ route('daily-statement-employee-attendance-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                        <label for="date_credit" class="control-label">Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date" placeholder="Date" value="{{ old('date') }}" tabindex="1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="date_credit" class="control-label">JackHammer : </label>
                                                        <select class="form-control account" name="account_id" id="attendance_account_id" tabindex="2" style="width: 100%">
                                                            <option value="">Select excavator</option>
                                                            @foreach($employeeAccounts as $employeeAccount)
                                                                <option value="{{ $employeeAccount->id }}">{{ $employeeAccount->account_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6">
                                                        <label for="bucket_reading" class="control-label"> Contractor : </label>
                                                        <input type="text" class="form-control" name="contractor_jackhammer" id="contractor_jackhammer" value="" tabindex="3" readonly>
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                                        <label for="bucket_reading" class="control-label"> Total Depth Of Pockets In Feet : </label>
                                                        <input type="text" class="form-control" name="bucket_reading" id="bucket_reading" value="" tabindex="3">
                                                    </div>
                                                </div>
                                                <div class="form-group" hidden>
                                                    <div class="col-sm-6"></div>
                                                    <div class="col-sm-6"></div>
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
                                            <h4>Jackhammer working list : {{ $today->format('d-m-Y') }}</h4>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jackhammer</th>
                                                <th>Contractor</th>
                                                <th>Depth Drilled</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employeeAttendance as $index => $attendance)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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
    <script src="/js/dailyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection