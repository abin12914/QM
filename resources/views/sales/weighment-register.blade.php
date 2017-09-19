@extends('layouts.app')
@section('title', 'Weighment Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Weighment
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Weighment Registration</li>
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
        @if(!empty($errors->first('sale_id')))
            <div class="alert alert-danger" id="alert-message">
                <p>{{$errors->first('sale_id')}}</p>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="box">
                        @if(!empty($sale))
                            <div class="box-header with-border">
                                <h3 class="box-title" style="float: left;">Weighment Registration</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                                <div class="box-tools pull-right">
                                        <a href="{{ route('sales-weighment-pending-view') }}">
                                            <button type="button" class="btn btn-default btn-sm active text-green">
                                                <i class="fa fa-level-up"> Back to list</i>
                                            </button>
                                        </a>
                                  </div>
                            </div><br>
                            <!-- form start -->
                            <form action="{{route('sales-weighment-register-action')}}" id="sale_weighment_registration_form" method="post" class="form-horizontal">
                                <div class="box-body">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                                    <div class="row">
                                        {{-- <div class="col-md-1"></div> --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="vehicle_number" class="col-md-2 control-label">Truck Number : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control prevent-edit" tabindex="20" id="vehicle_number" title="Do not edit this field" value="{{ $sale->vehicle->reg_number }}">
                                                </div>
                                                <label for="product" class="col-md-1 control-label">Product : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control prevent-edit" id="product" tabindex="21" title="Do not edit this field" value="{{ $sale->product->name }}">
                                                </div>
                                            </div><br>
                                            <div class="form-group">
                                                <label for="date" class="col-md-2 control-label">Date and Time : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control prevent-edit" id="date" title="Do not edit this field" tabindex="22"  value="{{ Carbon\Carbon::parse($sale->date_time)->format('d-m-Y H:m:i') }}">
                                                </div>
                                                <label for="purchaser" class="col-md-1 control-label">Purchaser : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control prevent-edit" id="purchaser" tabindex="23" title="Do not edit this field" value="{{ $sale->transaction->debitAccount->account_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="measure_volume_details">
                                                <div class="box-header with-border"></div><br>
                                                <div class="form-group">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-3 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                                        <label for="quantity" class="control-label"><b style="color: red;">* </b> Quantity :</label>
                                                        <input type="text" class="form-control decimal_number_only quantity" name="quantity" id="quantity" placeholder="Quantity" value="{{ old('quantity') }}" tabindex="1">
                                                        @if(!empty($errors->first('quantity')))
                                                            <p style="color: red;" >{{$errors->first('quantity')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-3 {{ !empty($errors->first('rate')) ? 'has-error' : '' }}">
                                                        <label for="rate" class="control-label"><b style="color: red;">* </b> Rate :</label>
                                                        <input type="text" class="form-control decimal_number_only rate" name="rate" id="rate" placeholder="Rate" value="{{ $sale->product->rate_ton }}" tabindex="2">
                                                        @if(!empty($errors->first('rate')))
                                                            <p style="color: red;" >{{$errors->first('rate')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('bill_amount')) ? 'has-error' : '' }}">
                                                        <label for="bill_amount" class="control-label">Bill Amount :</label>
                                                        <input type="text" class="form-control" name="bill_amount" id="bill_amount" placeholder="Bill amount" value="{{ !empty(old('bill_amount')) ? old('bill_amount') : '0' }}" readonly>
                                                        @if(!empty($errors->first('bill_amount')))
                                                            <p style="color: red;" >{{$errors->first('bill_amount')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-6 {{ !empty($errors->first('discount')) ? 'has-error' : '' }}">
                                                        <label for="discount" class="control-label"><b style="color: red;">* </b> Discount :</label>
                                                        <input type="text" class="form-control decimal_number_only discount" name="discount" id="discount" placeholder="Discount" value="{{ !empty(old('discount')) ? old('discount') : '0' }}" tabindex="3">
                                                        @if(!empty($errors->first('discount')))
                                                            <p style="color: red;" >{{$errors->first('discount')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 {{ !empty($errors->first('deducted_total')) ? 'has-error' : '' }}">
                                                        <label for="deducted_total" class="control-label">Total</label>
                                                        <input type="text" class="form-control" name="deducted_total" id="deducted_total" placeholder="Deducted balance" value="{{ !empty(old('deducted_total')) ? old('deducted_total') : '0' }}" readonly>
                                                        @if(!empty($errors->first('deducted_total')))
                                                            <p style="color: red;" >{{$errors->first('deducted_total')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"> </div><br>
                                    <div class="row">
                                        <div class="col-xs-3"></div>
                                        <div class="col-xs-3">
                                            <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="5">Cancel</button>
                                        </div>
                                        <div class="col-xs-3">
                                            <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4">Submit</button>
                                        </div>
                                        <!-- /.col -->
                                    </div><br>
                                </div>
                            </form>
                            <!-- /.form end -->
                        @else
                            <div class="alert alert-info" id="empty-message">
                                <h4>Something went wrong! Please try after relaoding the page</h4>
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
    <script src="/js/registration/weighmentRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection