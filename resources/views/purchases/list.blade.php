@extends('layouts.app')
@section('title', 'Purchase List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Purchase & Expense
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Purchase List</li>
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
        <div class="row  no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('purchases-list-search') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('product_id')) ? 'has-error' : '' }}">
                                            <label for="product_id" class="control-label">Product : </label>
                                            <select class="form-control" name="product_id" id="product_id" tabindex="3" style="width: 100%">
                                                @if(!empty($products) && (count($products) > 0))
                                                    <option value="">Select product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ ((old('product_id') == $product->id ) || $productId == $product->id) ? 'selected' : '' }}>{{ $product->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('product')))
                                                <p style="color: red;" >{{$errors->first('product')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6     {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label">Supplier : </label>
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
                                    </div>
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
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="10">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">Date / Ref. No.</th>
                                            <th style="width: 15%;">Product</th>
                                            <th style="width: 20%;">Supplier</th>
                                            <th style="width: 20%;">Description</th>
                                            <th style="width: 15%;">Bill Amount</th>
                                            <th style="width: 10%;" class="no-print">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($purchases))
                                            @foreach($purchases as $index=>$purchase)
                                                <tr>
                                                    <td>{{ $index + $purchases->firstItem() }}</td>
                                                    <td>{{ $purchase->date_time->format('d-m-Y') }}<i class="no-print"> / </i>
                                                        <b class="no-print bg-info text-red">{{ $purchase->transaction->id }}</b>
                                                    </td>
                                                    <td>{{ $purchase->purchasebleProduct->name }}</td>
                                                    <td>{{ $purchase->transaction->creditAccount->account_name }}</td>
                                                    <td>{{ $purchase->transaction->particulars }}</td>
                                                    <td>{{ $purchase->bill_amount }}</td>
                                                    <td class="no-print">
                                                        @if($purchase->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                            <form action="{{route('purchase-delete-action')}}" id="delete_{{ $purchase->id }}" method="post" style="float: left;">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                                <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($purchase->date_time)->format('d-m-Y') }}">
                                                                <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $purchase->id }}" data-transaction-id="{{ $purchase->transaction->id }}" type="button">
                                                                    <i class="fa fa-trash"> Delete</i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                <i class="fa fa-exclamation-circle"> No Access</i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if((Request::get('page') == $purchases->lastPage() || $purchases->lastPage() == 1))
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Total Amount</b></td>
                                                    <td></td>
                                                    <td><b>{{ $totalAmount }}</b></td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row  no-print">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        @if(!empty($purchases))
                                            {{ $purchases->appends(Request::all())->links() }}
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
    <div class="modal modal modal-danger" id="delete_confirmation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Confirm Action</h4>
                </div>
                <div class="modal-body">
                    <div id="modal_warning">
                        <div class="row">
                            <div class="col-sm-2">
                                <b id="modal_warning_record_id" class="pull-right"></b>
                            </div>
                            <div class="col-sm-10">
                                <p>
                                    <b> Are you sure to delete this record?</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="delete_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" id="delete_confirmation_modal_confirm" class="btn btn-primary" data-delete-modal-id="0" data-dismiss="modal">Confirm</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endsection
@section('scripts')
    <script src="/js/list/purchases.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection