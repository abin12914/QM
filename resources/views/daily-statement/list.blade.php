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
                            <li class="{{ Request::is('daily-statement/list/employee')? 'active' : '' }}"><a href="{{ Request::is('daily-statement/list/employee')? '#' : route('daily-statement-list-employee') }}">Employee Attendance List</a></li>
                            <li class="{{ Request::is('daily-statement/list/excavator')? 'active' : '' }}"><a href="{{ Request::is('daily-statement/list/excavator')? '#' : route('daily-statement-list-excavator') }}">Excavator Readings</a></li>
                            <li class="{{ Request::is('daily-statement/list/jackhammer')? 'active' : '' }}"><a href="{{ Request::is('daily-statement/list/jackhammer')? '#' : route('daily-statement-list-jackhammer') }}">Jack-Hammer Readings</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ Request::is('daily-statement/list/employee')? 'active' : '' }} tab-pane" id="employee_tab">
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
                                                                <td>{{ $index+1 }}</td>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($employeeAttendance))
                                                        {{ $employeeAttendance->links() }}
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
                                                            <td>{{ $index+1 }}</td>
                                                            <td>{{ $excavatorReading->date }}</td>
                                                            <td>{{ $excavatorReading->excavator->name }}</td>
                                                            <td>{{ $excavatorReading->transaction->creditAccount->account_name }}</td>
                                                            <td>{{ $excavatorReading->bucket_hour }}</td>
                                                            <td>{{ $excavatorReading->breaker_hour }}</td>
                                                            <td>{{ $excavatorReading->bata }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($excavatorReadings))
                                                        {{ $excavatorReadings->links() }}
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
                                                        <th>Total Pit Depth</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jackhammerReadings as $index => $jackhammerReading)
                                                        <tr>
                                                            <td>{{ $index+1 }}</td>
                                                            <td>{{ $jackhammerReading->date }}</td>
                                                            <td>{{ $jackhammerReading->jackhammer->name }}</td>
                                                            <td>{{ $jackhammerReading->jackhammer->account->account_name }}</td>
                                                            <td>{{ $jackhammerReading->total_pit_depth }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($jackhammerReadings))
                                                        {{ $jackhammerReadings->links() }}
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
    <script src="/js/dailyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection