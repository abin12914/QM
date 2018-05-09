@extends('layouts.app')
@section('title', 'Account Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account Statement
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Account Statement</li>
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
        @if(Session::has('fixed-message'))
            <div class="alert alert-warning" id="fixed-message">
                <h4>
                  {!! Session::get('fixed-message') !!}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <form action="{{ route('account-statement-list-search') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-4     {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label"><b style="color: red">*</b> Account : </label>
                                            <select class="form-control" name="account_id" id="account_id" tabindex="3" style="width: 100%">
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    <option value="">Select account</option>
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
                            <div class="row no-print">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="10">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Get</button>
                                </div>
                                {{-- <div class="col-md-5"></div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                </div> --}}
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                @if(!empty($selectedAccountName))
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
                                                                <span class="badge bg-light-blue" style="width:100%; font-size: 15px;">{{ $selectedAccountName }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-down"></i></span>&nbsp&nbsp Total Debit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-yellow" style="width:100%; font-size: 15px;">{{ round($totalOverviewDebit) }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-up"></i></span>&nbsp&nbsp Total Credit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-orange" style="width:100%; font-size: 15px;">{{ round($totalOverviewCredit) }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            @if($totalOverviewDebit >= $totalOverviewCredit)
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-dollar"></i></span>&nbsp&nbsp Balance To Get
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-green" style="width:100%; font-size: 15px;">{{ round(($totalOverviewDebit - $totalOverviewCredit)) }}</span>
                                                                </td>
                                                            @else
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-dollar"></i></span>&nbsp&nbsp Balance To Pay
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-red" style="width:100%; font-size: 15px;">{{ round(($totalOverviewCredit - $totalOverviewDebit)) }}</span>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="box-header">
                                        <div class="pad margin no-print">
                                            <div class="callout callout-default">
                                                <h4 style="color: green;">Debit - Credit Overview</h4>
                                                <div class="table-responsive">
                                                    <table class="table" style="color: orangered;">
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-down"></i></span>&nbsp&nbsp Total Debit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-yellow" style="width:100%; font-size: 15px;">{{ round($totalDebitAmount) }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-up"></i></span>&nbsp&nbsp Total Credit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-orange" style="width:100%; font-size: 15px;">{{ round($totalCreditAmount) }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            @if($totalDebitAmount >= $totalCreditAmount)
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-dollar"></i></span>&nbsp&nbsp Balance To Get
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-green" style="width:100%; font-size: 15px;">{{ round(($totalDebitAmount - $totalCreditAmount)) }}</span>
                                                                </td>
                                                            @else
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-dollar"></i></span>&nbsp&nbsp Balance To Pay
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-red" style="width:100%; font-size: 15px;">{{ round(($totalCreditAmount - $totalDebitAmount)) }}</span>
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
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 10%;">Date</th>
                                            <th style="width: 5%;">Ref No.</th>
                                            <th style="width: 50%;">Particulars</th>
                                            <th style="width: 15%;">Debit</th>
                                            <th style="width: 15%;">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($transactions))
                                            @foreach($transactions as $index => $transaction)
                                                <tr>
                                                    <td>{{ $index + $transactions->firstItem() }}</td>
                                                    <td>{{ Carbon\Carbon::parse($transaction->date_time)->format('d-m-Y') }}</td>
                                                    <td>{{ $transaction->id }}</td>
                                                    <td>{{ $transaction->particulars }}</td>
                                                    @if($transaction->debit_account_id == $accountId)
                                                        <td>{{ round($transaction->amount, 2) }}</td>
                                                        <td>-</td>
                                                    @elseif($transaction->credit_account_id == $accountId)
                                                        <td>-</td>
                                                        <td>{{ round($transaction->amount, 2) }}</td>
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            @if(Request::get('page') == $transactions->lastPage() || $transactions->lastPage() == 1)
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <td><b>Sub Total</b></td>
                                                    <td><b>{{ round($subtotalDebit, 2) }}</b></td>
                                                    <td><b>{{ round($subtotalCredit, 2) }}</b></td>
                                                </tr>
                                                <tr class="bg-gray">
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <td>Summary</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    @if($subtotalDebit <= $subtotalCredit )
                                                        <td><b>Sub total Balance </b>- [{{ round($subtotalCredit, 2) }} - {{ round($subtotalDebit, 2) }}]</td>
                                                        <td></td>
                                                        <td><b>{{ round(($subtotalCredit - $subtotalDebit), 2) }}</b></td>
                                                    @else
                                                        <td><b>Sub total Balance </b>- [{{ round($subtotalDebit, 2) }} - {{ round($subtotalCredit, 2) }}]</td>
                                                        <td><b>{{ round(($subtotalDebit - $subtotalCredit), 2) }}</b></td>
                                                        <td></td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    @if($obDebitAmount <= $obCreditAmount )
                                                        <td><b>Old Balance </b>- [{{ round($obCreditAmount, 2) }} - {{ round($obDebitAmount, 2) }}]</td>
                                                        <td></td>
                                                        <td><b>{{ round(($obCreditAmount- $obDebitAmount), 2) }}</b></td>
                                                    @else
                                                        <td><b>Old Balance </b>- [{{ round($obDebitAmount, 2) }} - {{ round($obCreditAmount, 2) }}]</td>
                                                        <td><b>{{ round(($obDebitAmount - $obCreditAmount), 2) }}</b></td>
                                                        <td></td>
                                                    @endif    
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <td><b>Total Amount</b></td>
                                                   <td><b>{{ round($totalDebit, 2) }}</b></td>
                                                   <td><b>{{ round($totalCredit, 2) }}</b></td>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    @if($totalDebit <= $totalCredit)
                                                        <td><b>Balance </b>[ {{ round($totalCredit) }} - {{ round($totalDebit) }} ]</td>
                                                        <td>{{ round(($totalCredit - $totalDebit), 2) }}</td>
                                                        <td></td>
                                                    @else
                                                        <td><b>OVER </b>[ {{ round($totalDebit) }} - {{ round($totalCredit) }} ]</td>
                                                        <td></td>
                                                        <td><b>{{ round(($totalDebit - $totalCredit), 2) }}</b></td>
                                                    @endif
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