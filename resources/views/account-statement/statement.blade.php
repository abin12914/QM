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
                        <form action="{{ route('account-statement-list-search') }}" method="get" class="form-horizontal" multipart-form-data>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-4 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label"><b style="color: red">*</b> Account : </label>
                                            <select class="form-control" name="account_id" id="account_id" tabindex="3" style="width: 100%">
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    <option value="">Select employee account</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ ((old('account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('account_id')))
                                                <p style="color: red;" >{{$errors->first('account_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">Start Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('from_date') }}" tabindex="1">
                                            @if(!empty($errors->first('from_date')))
                                                <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
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
                            <div class="box-header with-border"></div><br>
                        </form>
                        <!-- /.form end -->
                    </div>
                    @if(!empty($selectedAccountName))
                        <div class="box-header">
                            <h4>Ledger of <b>{{ $selectedAccountName }}</b> {{ !empty($fromDate) ? ' [ '.$fromDate.' - ' : '[ starting - ' }} {{ !empty($toDate) ? $toDate.' ]' : 'end ]' }}</h4>
                        </div>
                        <div class="col-md-3"></div>
                    <div class="col-md-6">
                        
                    </div>
                    @endif
                    <!-- /.box-header -->
                    <div class="box-body">
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
                                                    @if($transaction->debit_account_id == $accountId)
                                                        <td>{{ $transaction->amount }}</td>
                                                        <td>0</td>
                                                         <?php /*$debitAmount = $debitAmount + $transaction->amount;*/ ?>
                                                    @elseif($transaction->credit_account_id == $accountId)
                                                        <td>0</td>
                                                        <td>{{ $transaction->amount }}</td>
                                                        <?php /*$creditAmount = $creditAmount + $transaction->amount;*/ ?>
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
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
                                            {{ $transactions->links() }}
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
@section('scripts')
    <script src="/js/statements/accountStatement.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection