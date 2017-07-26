@extends('layouts.app')
@section('title', 'Account Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Accounts
            {{-- <small>List</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Account</a></li>
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
                    <div class="box-header">
                        <h3 class="box-title">Account List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('account-list') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('relation')) ? 'has-error' : '' }}">
                                            <label for="relation" class="control-label">Relation Or Type : </label>
                                            <select class="form-control" name="relation" id="relation" tabindex="3" style="width: 100%">
                                                <option value="" {{ (empty($relation) || (empty(old('relation')) && $relation == 0)) ? 'selected' : '' }}>Select transaction type</option>
                                                <option value="real" {{ (!empty($relation) && ((old('relation') == 'real' ) || $relation == 'real')) ? 'selected' : '' }}>Real Account</option>
                                                <option value="nominal" {{ (!empty($relation) && (old('relation') == 'nominal' || $relation == 'nominal')) ? 'selected' : '' }}>Nominal</option>
                                                <option value="personal" {{ (!empty($relation) && (old('relation') == 'personal' || $relation == 'personal')) ? 'selected' : '' }}>Personal</option>
                                            </select>
                                            @if(!empty($errors->first('relation')))
                                                <p style="color: red;" >{{$errors->first('relation')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6     {{ !empty($errors->first('cash_voucher_account_id')) ? 'has-error' : '' }}">
                                            <label for="cash_voucher_account_id" class="control-label">Account : </label>
                                            <select class="form-control" name="cash_voucher_account_id" id="cash_voucher_account_id" tabindex="3" style="width: 100%">
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    <option value="">Select employee account</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ ((old('cash_voucher_account_id') == $account->id ) || (!empty($accountId) && $accountId == $account->id)) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('cash_voucher_account_id')))
                                                <p style="color: red;" >{{$errors->first('cash_voucher_account_id')}}</p>
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
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Account Name</th>
                                            <th>Type</th>
                                            <th>Relation</th>
                                            <th>Account Holder/Head</th>
                                            <th>Opening Credit</th>
                                            <th>Opening Debit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($accounts))
                                            @foreach($accounts as $index => $account)
                                                <tr>
                                                    {{-- <td>{{ (!empty(Request::get('page')) ? Request::get('page')-1 : 0) * 10 + ($index+1) }}</td> --}}
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $account->account_name }}</td>
                                                    <td>{{ $account->type }}</td>
                                                    <td>{{ $account->relation }}</td>
                                                    <td>{{ $account->accountDetail->name }}</td>
                                                    @if($account->financial_status == 'debit')
                                                        <td>0</td>
                                                        <td>{{ $account->opening_balance }}</td>
                                                    @elseif($account->financial_status == 'credit')
                                                        <td>{{ $account->opening_balance }}</td>
                                                        <td>0</td>
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
                                        @if(!empty($accounts))
                                            {{ $accounts->links() }}
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