@extends('layouts.app')
@section('title', 'Monthly Resource List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Monthly Resource<small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Monthly Resource List</li>
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
                        <ul class="nav nav-tabs">
                            <li class="{{ Request::is('monthly-statement/list/employee')? 'active' : '' }}"><a href="{{ Request::is('monthly-statement/list/employee')? '#' : route('monthly-statement-list-employee') }}">Employee Attendance List</a></li>
                            <li class="{{ Request::is('monthly-statement/list/excavator')? 'active' : '' }}"><a href="{{ Request::is('monthly-statement/list/excavator')? '#' : route('monthly-statement-list-excavator') }}">Excavator Readings</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ Request::is('monthly-statement/list/employee')? 'active' : '' }} tab-pane" id="employee_tab">
                                <!-- box-header -->
                                <div class="box-header">
                                    <form action="{{ route('monthly-statement-list-employee') }}" method="get" class="form-horizontal" multipart-form-data>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('salary_employee_id')) ? 'has-error' : '' }}">
                                                        <label for="salary_employee_id" class="control-label">Empoyee Name : </label>
                                                        <select class="form-control" name="salary_employee_id" id="salary_employee_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($employees) && (count($employees) > 0))
                                                                <option value="">Select employee</option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{ $employee->id }}" {{ ((old('salary_employee_id') == $employee->id ) || $employeeId == $employee->id) ? 'selected' : '' }}>{{ $employee->account->accountDetail->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('salary_employee_id')))
                                                            <p style="color: red;" >{{$errors->first('salary_employee_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6     {{ !empty($errors->first('salary_account_id')) ? 'has-error' : '' }}">
                                                        <label for="salary_account_id" class="control-label">Employee Account : </label>
                                                        <select class="form-control" name="salary_account_id" id="salary_account_id" tabindex="3" style="width: 100%">
                                                            @if(!empty($accounts) && (count($accounts) > 0))
                                                                <option value="">Select employee account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ ((old('salary_account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('salary_account_id')))
                                                            <p style="color: red;" >{{$errors->first('salary_account_id')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6 {{ !empty($errors->first('salary_from_date')) ? 'has-error' : '' }}">
                                                        <label for="salary_from_date" class="control-label">Start Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="salary_from_date" id="salary_from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('salary_from_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('salary_from_date')))
                                                            <p style="color: red;" >{{$errors->first('salary_from_date')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('salary_to_date')) ? 'has-error' : '' }}">
                                                        <label for="salary_to_date" class="control-label">End Date : </label>
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="salary_to_date" id="salary_to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('salary_to_date') }}" tabindex="1">
                                                        @if(!empty($errors->first('salary_to_date')))
                                                            <p style="color: red;" >{{$errors->first('salary_to_date')}}</p>
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
                                                        <th>Employee Name</th>
                                                        <th>Account Name</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Wage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($employeeSalary) > 0)
                                                        @foreach($employeeSalary as $index => $salary)
                                                            <tr>
                                                                <td>{{ $index+1 }}</td>
                                                                <td>{{ $salary->employee->account->accountDetail->name }}</td>
                                                                <td>{{ $salary->employee->account->account_name }}</td>
                                                                <td>{{ $salary->from_date }}</td>
                                                                <td>{{ $salary->to_date }}</td>
                                                                <td>{{ $salary->salary }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($employeeSalary))
                                                        {{ $employeeSalary->appends(Request::all())->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ Request::is('monthly-statement/list/excavator')? 'active' : '' }} tab-pane" id="excavators_tab">
                                <!-- box-header -->
                                <div class="box-header">
                                    <form action="{{ route('monthly-statement-list-excavator') }}" method="get" class="form-horizontal" multipart-form-data>
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
                                                        <th>Excavator</th>
                                                        <th>Contractor Account</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Rent</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($excavatorRent) > 0)
                                                        @foreach($excavatorRent as $index => $rent)
                                                            <tr>
                                                                <td>{{ $index+1 }}</td>
                                                                <td>{{ $rent->excavator->name }}</td>
                                                                <td>{{ $rent->excavator->account->account_name }}</td>
                                                                <td>{{ $rent->from_date }}</td>
                                                                <td>{{ $rent->to_date }}</td>
                                                                <td>{{ $rent->rent }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($excavatorRent))
                                                        {{ $excavatorRent->appends(Request::all())->links() }}
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
    <script src="/js/list/monthlyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection