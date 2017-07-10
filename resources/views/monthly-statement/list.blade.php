@extends('layouts.app')
@section('title', 'Purchases')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Daily Statements
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Daily statement</a></li>
            <li class="active">List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {{ Session::get('message') }}
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
                                                        {{ $employeeSalary->links() }}
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
                                                        {{ $excavatorRent->links() }}
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
    <script src="/js/monthlyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection