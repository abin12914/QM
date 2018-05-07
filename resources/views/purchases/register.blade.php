@extends('layouts.app')
@section('title', 'Purchase & Expense Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Purchase & Expense
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Purchase & Expense Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row  no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="float: left;">Purchase & Expense Registration</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- form start -->
                        <form action="{{route('purchases-register-action')}}" id="purchase_form" method="post" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="transaction_type_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Transaction Type : </label>
                                            <div class="col-lg-5 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_credit" value="1" {{ empty(old('transaction_type')) || old('transaction_type') == '1' ? 'checked' : ''}}>
                                                    </span>
                                                    <label for="transaction_type_credit" class="form-control" tabindex="9">Credit</label>
                                                </div>
                                                @if(!empty($errors->first('transaction_type')))
                                                    <p style="color: red;" >{{$errors->first('transaction_type')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-lg-5 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_cash" value="2" {{ old('transaction_type') == '2' ? 'checked' : ''}}>
                                                    </span>
                                                    <label for="transaction_type_cash" class="form-control" tabindex="10">Cash</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" id="supplier_div" {{ (old('transaction_type') == '2') ? 'hidden' : '' }}>
                                            <label for="supplier" class="col-sm-2 control-label"><b style="color: red;">* </b> Supplier : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('supplier_account_id')) ? 'has-error' : '' }}">
                                                <select name="supplier_account_id" class="form-control supplier" id="supplier" tabindex="1" style="width: 100%" {{ old('transaction_type') == '2' ? 'disabled' : '' }}>
                                                    <option value="" {{ empty(old('supplier_account_id')) ? 'selected' : '' }}>Select supplier</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ (old('supplier_account_id') == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                    @endforeach
                                                </select>
                                                @if(!empty($errors->first('supplier_account_id')))
                                                    <p style="color: red;" >{{$errors->first('supplier_account_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="date" class="col-sm-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                            <div class="col-sm-5 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date" placeholder="Date" value="{{ old('date') }}" tabindex="11">
                                                @if(!empty($errors->first('date')))
                                                    <p style="color: red;" >{{$errors->first('date')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-sm-5 {{ !empty($errors->first('time')) ? 'has-error' : '' }}">
                                                <div class="bootstrap-timepicker">
                                                    <input type="text" class="form-control timepicker" name="time" id="time" placeholder="Time" value="{{ old('time') }}" tabindex="12">
                                                </div>
                                                @if(!empty($errors->first('time')))
                                                    <p style="color: red;" >{{$errors->first('time')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="product" class="col-sm-2 control-label"><b style="color: red;">* </b> Product : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('product_id')) ? 'has-error' : '' }}">
                                                <select name="product_id" class="form-control product" id="product" tabindex="2" style="width: 100%">
                                                    <option value="" {{ empty(old('product_id')) ? 'selected' : '' }}>Select product</option>
                                                    @foreach($purchasebleProducts as $purchasebleproduct)
                                                        <option value="{{ $purchasebleproduct->id }}" {{ (old('product_id') == $purchasebleproduct->id) ? 'selected' : '' }}>{{ $purchasebleproduct->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if(!empty($errors->first('product_id')))
                                                    <p style="color: red;" >{{$errors->first('product_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group" id="explosive_quantity_div" {{ (old('product_id') != '2') ? 'hidden' : '' }}>
                                            <label for="explosive_quantity_div" class="col-sm-2 control-label"><b style="color: red;">* </b> No of Cap & Gel : </label>
                                            <div class="col-sm-5 {{ !empty($errors->first('explosive_quantity_cap')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control number_only" name="explosive_quantity_cap" id="explosive_quantity_cap" placeholder="No of cap/detonator" value="{{ old('explosive_quantity_cap') }}">
                                                @if(!empty($errors->first('explosive_quantity_cap')))
                                                    <p style="color: red;" >{{$errors->first('explosive_quantity_cap')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-sm-5 {{ !empty($errors->first('explosive_quantity_gel')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control number_only" name="explosive_quantity_gel" id="explosive_quantity_gel" placeholder="No of gel" value="{{ old('explosive_quantity_gel') }}">
                                                @if(!empty($errors->first('explosive_quantity_gel')))
                                                    <p style="color: red;" >{{$errors->first('explosive_quantity_gel')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="bill_no" class="col-sm-2 control-label">Bill Number :</label>
                                            <div class="col-sm-10 {{ !empty($errors->first('bill_no')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control number_only" name="bill_no" id="bill_no" placeholder="Bill number" value="{{ old('bill_no') }}" tabindex="3">
                                                @if(!empty($errors->first('bill_no')))
                                                    <p style="color: red;" >{{$errors->first('bill_no')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-sm-2 control-label">Description :</label>
                                            <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control" name="description" id="description" placeholder="Description" value="{{ old('description') }}" tabindex="4">
                                                @if(!empty($errors->first('description')))
                                                    <p style="color: red;" >{{$errors->first('description')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="bill_amount" class="col-sm-2 control-label"><b style="color: red;">* </b> Bill Amount :</label>
                                            <div class="col-sm-10 {{ !empty($errors->first('bill_amount')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control decimal_number_only" name="bill_amount" id="bill_amount" placeholder="Bill amount" value="{{ old('bill_amount') }}" tabindex="5">
                                                @if(!empty($errors->first('bill_amount')))
                                                    <p style="color: red;" >{{$errors->first('bill_amount')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-xs-3"></div>
                                    <div class="col-xs-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="7">Clear</button>
                                    </div>
                                    <div class="col-xs-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="6">Submit</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row  no-print">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last 5 Purchase Records</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($purchaseRecords))
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Date / Ref. No.</th>
                                        <th style="width: 10%;">Product</th>
                                        <th style="width: 15%;">Supplier</th>
                                        <th style="width: 35%;">Description</th>
                                        <th style="width: 15%;">Bill Amount</th>
                                        <th style="width: 10%;" class="no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseRecords as $purchase_record)
                                        <tr>
                                            <td>
                                                {{ Carbon\Carbon::parse($purchase_record->date_time)->format('d-m-Y') }}
                                                <i class="no-print"> / </i>
                                                <b class="no-print bg-info text-red">{{ $purchase_record->transaction->id }}
                                            </td>
                                            <td>{{ $purchase_record->purchasebleProduct->name }}</td>
                                            <td>{{ $purchase_record->transaction->creditAccount->account_name }}</td>
                                            <td>{{ $purchase_record->transaction->particulars }}</td>
                                            <td>{{ $purchase_record->bill_amount }}</td>
                                            <td class="no-print">
                                                @if($purchase_record->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                    <form action="{{route('purchase-delete-action')}}" id="delete_{{ $purchase_record->id }}" method="post" style="float: left;">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="purchase_id" value="{{ $purchase_record->id }}">
                                                        <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($purchase_record->date_time)->format('d-m-Y') }}">
                                                        <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $purchase_record->id }}" data-transaction-id="{{ $purchase_record->transaction->id }}" type="button">
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
                                </tbody>
                            </table>
                        @else
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <label>No purchase records available to show!!</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
    <script src="/js/registration/purchaseRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection