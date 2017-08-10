@extends('layouts.app')
@section('title', 'Transaction Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Transaction Statement
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transaction Statement</li>
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
        <div class="row no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <form action="{{ route('daily-statement-list-search') }}" method="get" class="form-horizontal" multipart-form-data>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">Start Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('from_date') }}" tabindex="1">
                                            @if(!empty($errors->first('from_date')))
                                                <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                                            <label for="to_date" class="control-label">End Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="to_date" id="to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('to_date') }}" tabindex="1">
                                            @if(!empty($errors->first('to_date')))
                                                <p style="color: red;" >{{$errors->first('to_date')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        @if(empty($fromDate) && !empty($toDate))
                            <h4>Date : {{ $toDate }}</h4>
                        @elseif(!empty($fromDate) && empty($toDate))
                            <h4>Date : {{ $fromDate }}</h4>
                        @elseif(!empty($fromDate) && !empty($toDate))
                            <h4>From : {{ $fromDate }} &nbsp;&nbsp;&nbsp; To : {{ $toDate }}</h4>
                        @endif
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($sales))
                                    <tr>
                                        <td>Sales</td>
                                        <td>-</td>
                                        <td>{{ $sales }}</td>
                                    </tr>
                                @endif
                                @if(!empty($purchases))
                                    <tr>
                                        <td>Purchases and other expences</td>
                                        <td>{{ $purchases }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($labourWage))
                                    <tr>
                                        <td>Labour Wage</td>
                                        <td>{{ $labourWage }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($excavatorReadingRent))
                                    <tr>
                                        <td>Excavator reading rent</td>
                                        <td>{{ $excavatorReadingRent }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($jackhammerRent))
                                    <tr>
                                        <td>Jackhammer rent</td>
                                        <td>{{ $jackhammerRent }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($employeeSalary))
                                    <tr>
                                        <td>Employee Salary</td>
                                        <td>{{ $employeeSalary }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($excavatorMonthlyRent))
                                    <tr>
                                        <td>Excavator monthly rent</td>
                                        <td>{{ $excavatorMonthlyRent }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                                @if(!empty($royalty))
                                    <tr>
                                        <td>Royalty</td>
                                        <td>{{ $royalty }}</td>
                                        <td>-</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <th>{{ $totalDebit }}</th>
                                    <th>{{ $totalCredit }}</th>
                                </tr>
                                <tr>
                                    @if($totalDebit <= $totalCredit)
                                        <th>{{ 'Balance' }}</th>
                                        <th>{{ $totalCredit - $totalDebit }}</th>
                                        <th></th>
                                    @else
                                        <th>{{ 'Over expence' }}</th>
                                        <th></th>
                                        <th>{{ $totalDebit - $totalCredit }}</th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/statements/DailyStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection