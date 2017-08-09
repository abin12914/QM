@extends('layouts.app')
@section('title', 'Purchase Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Purchase
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Purchase Registration</li>
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
                            <h3 class="box-title" style="float: left;">Purchase Registration</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- form start -->
                        <form action="{{route('purchases-register-action')}}" id="purchase_form" method="post" class="form-horizontal" multipart-form-data>
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
                                        <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="6">Submit</button>
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
                                        <th>Date & Time</th>
                                        <th>Product</th>
                                        <th>Supplier</th>
                                        <th>Bill Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseRecords as $purchase_record)
                                        <tr>
                                            <td>{{ $purchase_record->date_time }}</td>
                                            <td>{{ $purchase_record->purchasebleProduct->name }}</td>
                                            <td>{{ $purchase_record->transaction->creditAccount->account_name }}</td>
                                            <td>{{ $purchase_record->bill_amount }}</td>
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
</div>
@endsection
@section('scripts')
    <script src="/js/registration/purchaseRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection