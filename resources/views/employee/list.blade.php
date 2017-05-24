@extends('layouts.app')
@section('title', 'Employees')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Employess
            {{-- <small>List</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Employee</a></li>
            <li class="active">List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
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
                    <div class="box-header">
                        <h3 class="box-title">Employee List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Type</th>
                                            <th>Account Name</th>
                                            <th>Salary</th>
                                            <th>Wage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($employees))
                                            @foreach($employees as $employee)
                                                <tr>
                                                    <td>{{ $employee->account->accountDetail->name }}</td>
                                                    <td>{{ $employee->account->accountDetail->phone }}</td>
                                                    <td>{{ $employee->employee_type }}</td>
                                                    <td>{{ $employee->account->account_name }}</td>
                                                    @if($employee->employee_type == 'staff')
                                                        <td>{{ $employee->salary }}</td>
                                                        <td>0</td>
                                                    @elseif($employee->employee_type == 'labour')
                                                        <td>0</td>
                                                        <td>{{ $employee->wage }}</td>
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th><th></th><th></th><th></th><th></th><th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        @if(!empty($employees))
                                            {{ $employees->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
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