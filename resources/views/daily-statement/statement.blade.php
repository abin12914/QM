@extends('layouts.app')
@section('title', 'Account Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account Statement
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Account Statement</a></li>
            <li class="active">Statement</li>
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
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                @if(!empty($totalDebit) && !empty($totalCredit))
                                    <div class="box-header">
                                        <div class="pad margin no-print">
                                            <div class="callout callout-default">
                                                <h4 style="color: green;">Account Overview</h4>
                                                <div class="table-responsive">
                                                    <table class="table" style="color: orangered;">
                                                        <tr>
                                                            <th style="width:45%">
                                                                <span class="badge bg-black"><i class="fa fa-book"></i></span>&nbsp&nbsp Account Name
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-light-blue" style="width:100%;">{{ $selectedAccountName }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-down"></i></span>&nbsp&nbsp Total Debit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-yellow" style="width:100%">{{ $totalDebit }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-up"></i></span>&nbsp&nbsp Total Credit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-orange" style="width:100%">{{ $totalCredit }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            @if($totalDebit >= $totalCredit)
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-dollar"></i></span>&nbsp&nbsp Balance To Get
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-green" style="width:100%">{{ $totalDebit - $totalCredit }}</span>
                                                                </td>
                                                            @else
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-dollar"></i></span>&nbsp&nbsp Balance To Pay
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-red" style="width:100%">{{ $totalCredit - $totalDebit }}</span>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        @if(!empty($selectedAccountName))
                            <div class="box-header">
                                <h4>Ledger of <b>{{ $selectedAccountName }}</b> {{ !empty($fromDate) ? ' [ '.$fromDate.' - ' : '[ starting - ' }} {{ !empty($toDate) ? $toDate.' ]' : 'end ]' }}</h4>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date & Time</th>
                                            <th>Particulars</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($transactions))
                                            @foreach($transactions as $index => $transaction)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $transaction->date_time }}</td>
                                                    <td>{{ $transaction->particulars }}</td>
                                                    <td>{{ $transaction->amount }}</td>
                                                    <td>{{ $transaction->amount }}</td>
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
                                        @if(!empty($transactions))
                                            {{ $transactions->appends(Request::all())->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="/js/statements/accountStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection