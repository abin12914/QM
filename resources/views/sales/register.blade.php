@extends('layouts.app')
@section('title', 'Sale Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sale
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale Registration</li>
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
                <!-- nav-tabs-custom -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="{{ (empty(old('transaction_type')) || old('transaction_type') == 'credit') ? 'active' : '' }}"><a href="#credit_sale_tab" data-toggle="tab">Credit Sale</a></li>
                        <li class="{{ (old('transaction_type') == 'cash') ? 'active' : '' }}"><a href="#cash_sale_tab" data-toggle="tab">Cash Sale</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="{{ (empty(old('transaction_type')) || old('transaction_type') == 'credit') ? 'active' : '' }} tab-pane" id="credit_sale_tab">
                            <!-- form start -->
                            <form action="{{route('credit-sales-register-action')}}" id="credit_sale_form" method="post" class="form-horizontal" multipart-form-data>
                                <div class="box-body">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="transaction_type" value="credit">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="vehicle_number_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Truck Number : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('vehicle_id')) ? 'has-error' : '' }}">
                                                    <select name="vehicle_id" class="form-control vehicle_number" id="vehicle_number_credit" tabindex="1" style="width: 100%">
                                                        <option value="">Select truck number</option>
                                                        @foreach($vehicles as $vehicle)
                                                            <option value="{{ $vehicle->id }}" {{ (old('vehicle_id') == $vehicle->id) ? 'selected' : '' }} data-volume="{{ $vehicle->volume }}" data-bodytype="{{ $vehicle->body_type }}">{{ $vehicle->reg_number }} - {{ $vehicle->vehicleType->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('vehicle_id')))
                                                        <p style="color: red;" >{{$errors->first('vehicle_id')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="purchaser_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Purchaser : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('purchaser_account_id')) ? 'has-error' : '' }}">
                                                    <select name="purchaser_account_id" class="form-control purchaser" id="purchaser_credit" tabindex="2" style="width: 100%">
                                                        <option value="" {{ empty(old('purchaser_account_id')) ? 'selected' : '' }}>Select purchaser</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}" {{ (old('purchaser_account_id') == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('purchaser_account_id')))
                                                        <p style="color: red;" >{{$errors->first('purchaser_account_id')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="date_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                                <div class="col-sm-5 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date_credit" placeholder="Date" value="{{ old('date') }}" tabindex="22">
                                                    @if(!empty($errors->first('date')))
                                                        <p style="color: red;" >{{$errors->first('date')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-sm-5 {{ !empty($errors->first('time')) ? 'has-error' : '' }}">
                                                    <div class="bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker" name="time" id="time_credit" placeholder="Time" value="{{ old('time') }}" tabindex="23">
                                                    </div>
                                                    @if(!empty($errors->first('time')))
                                                        <p style="color: red;" >{{$errors->first('time')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="product_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Product : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('product_id')) ? 'has-error' : '' }}">
                                                    <select name="product_id" class="form-control product" id="product_credit" tabindex="3" style="width: 100%">
                                                        <option value="" {{ empty(old('product_id')) ? 'selected' : '' }}>Select product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ (old('product_id') == $product->id) ? 'selected' : '' }} data-rate-feet="{{ $product->rate_feet }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('product_id')))
                                                        <p style="color: red;" >{{$errors->first('product_id')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="measure_type_volume_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Measure Type : </label>
                                                <div class="col-lg-5 {{ !empty($errors->first('measure_type')) ? 'has-error' : '' }}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="measure_type" class="measure_type" id="measure_type_volume_credit" value="1" {{ empty(old('measure_type')) || old('measure_type') == '1' ? 'checked' : ''}}>
                                                        </span>
                                                        <label for="measure_type_volume_credit" class="form-control" tabindex="20">Volume</label>
                                                    </div>
                                                    @if(!empty($errors->first('measure_type')))
                                                        <p style="color: red;" >{{$errors->first('measure_type')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-lg-5 {{ !empty($errors->first('measure_type')) ? 'has-error' : '' }}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="measure_type" class="measure_type" id="measure_type_weighment_credit" value="2" {{ old('measure_type') == '2' ? 'checked' : ''}}>
                                                        </span>
                                                        <label for="measure_type_weighment_credit" class="form-control" tabindex="21">Weighment</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="measure_volume_details" {{ old('measure_type') == '2' ? 'hidden' : ''}}>
                                                <div class="box-header with-border"></div><br>
                                                <div class="form-group">
                                                    <label for="quantity_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Quantity x Rate :</label>
                                                    <div class="col-sm-2 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control decimal_number_only quantity" name="quantity" id="quantity_credit" placeholder="Quantity" value="{{ old('quantity') }}" tabindex="4">
                                                        @if(!empty($errors->first('quantity')))
                                                            <p style="color: red;" >{{$errors->first('quantity')}}</p>
                                                        @endif
                                                    </div>
                                                    <label for="rate_credit" class="col-sm-1 control-label">x</label>
                                                    <div class="col-sm-2 {{ !empty($errors->first('rate')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control decimal_number_only rate" name="rate" id="rate_credit" placeholder="Rate" value="{{ old('rate') }}" tabindex="5">
                                                        @if(!empty($errors->first('rate')))
                                                            <p style="color: red;" >{{$errors->first('rate')}}</p>
                                                        @endif
                                                    </div>
                                                    <label for="bill_amount_credit" class="col-sm-1 control-label">=</label>
                                                    <div class="col-sm-4 {{ !empty($errors->first('bill_amount')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control" name="bill_amount" id="bill_amount_credit" placeholder="Bill amount" value="{{ !empty(old('bill_amount')) ? old('bill_amount') : '0' }}" readonly>
                                                        @if(!empty($errors->first('bill_amount')))
                                                            <p style="color: red;" >{{$errors->first('bill_amount')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="discount_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Discount :</label>
                                                    <div class="col-sm-5 {{ !empty($errors->first('discount')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control decimal_number_only discount" name="discount" id="discount_credit" placeholder="Discount" value="{{ !empty(old('discount')) ? old('discount') : '0' }}" tabindex="6">
                                                        @if(!empty($errors->first('discount')))
                                                            <p style="color: red;" >{{$errors->first('discount')}}</p>
                                                        @endif
                                                    </div>
                                                    <label for="deducted_total_credit" class="col-sm-1 control-label">=</label>
                                                    <div class="col-sm-4 {{ !empty($errors->first('deducted_total')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control" name="deducted_total" id="deducted_total_credit" placeholder="Deducted balance" value="{{ !empty(old('deducted_total')) ? old('deducted_total') : '0' }}" readonly>
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
                                            <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                                        </div>
                                        <div class="col-xs-3">
                                            <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="7">Submit</button>
                                        </div>
                                        <!-- /.col -->
                                    </div><br>
                                </div>
                            </form>
                            <!-- /.form end -->
                        </div>
                        <!-- /.tab-pane -->
                        <div class="{{ (old('transaction_type') == 'cash') ? 'active' : '' }} tab-pane" id="cash_sale_tab">
                            <!-- form start -->
                            <form action="{{route('cash-sales-register-action')}}" id="cash_sale_form" method="post" class="form-horizontal" multipart-form-data>
                                <div class="box-body">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="transaction_type" value="cash">
                                    <input type="hidden" name="measure_type_cash" value="1">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="vehicle_number_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Truck Number : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('vehicle_id_cash')) ? 'has-error' : '' }}">
                                                    <select name="vehicle_id_cash" class="form-control vehicle_number" id="vehicle_number_cash" tabindex="1" style="width: 100%">
                                                        <option value="">Select truck number</option>
                                                        @foreach($vehicles as $vehicle)
                                                            <option value="{{ $vehicle->id }}" {{ (old('vehicle_id_cash') == $vehicle->id) ? 'selected' : '' }} data-volume="{{ $vehicle->volume }}" data-bodytype="{{ $vehicle->body_type }}">{{ $vehicle->reg_number }} - {{ $vehicle->vehicleType->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('vehicle_id_cash')))
                                                        <p style="color: red;" >{{$errors->first('vehicle_id_cash')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            {{-- <div class="form-group">
                                                <label for="purchaser_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Purchaser : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('purchaser_account_id_cash')) ? 'has-error' : '' }}">
                                                    <select name="purchaser_account_id_cash" class="form-control purchaser" id="purchaser_cash" tabindex="2" style="width: 100%">
                                                        <option value="" {{ empty(old('purchaser_account_id_cash')) ? 'selected' : '' }}>Select purchaser</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}" {{ (old('purchaser_account_id_cash') == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('purchaser_account_id_cash')))
                                                        <p style="color: red;" >{{$errors->first('purchaser_account_id_cash')}}</p>
                                                    @endif
                                                </div>
                                            </div> --}}
                                            <div class="form-group">
                                                <label for="date_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                                <div class="col-sm-5 {{ !empty($errors->first('date_cash')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only datepicker" name="date_cash" id="date_cash" placeholder="Date" value="{{ old('date_cash') }}" tabindex="22">
                                                    @if(!empty($errors->first('date_cash')))
                                                        <p style="color: red;" >{{$errors->first('date_cash')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-sm-5 {{ !empty($errors->first('time_cash')) ? 'has-error' : '' }}">
                                                    <div class="bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker" name="time_cash" id="time_cash" placeholder="Time" value="{{ old('time_cash') }}" tabindex="23">
                                                    </div>
                                                    @if(!empty($errors->first('time_cash')))
                                                        <p style="color: red;" >{{$errors->first('time_cash')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="product_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Product : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('product_id_cash')) ? 'has-error' : '' }}">
                                                    <select name="product_id_cash" class="form-control product" id="product_cash" tabindex="3" style="width: 100%">
                                                        <option value="" {{ empty(old('product_id_cash')) ? 'selected' : '' }}>Select product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ (old('product_id_cash') == $product->id) ? 'selected' : '' }} data-rate-feet="{{ $product->rate_feet }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('product_id_cash')))
                                                        <p style="color: red;" >{{$errors->first('product_id_cash')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="box-header with-border"></div><br>
                                            <div class="form-group">
                                                <label for="quantity_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Quantity x Rate :</label>
                                                <div class="col-sm-2 {{ !empty($errors->first('quantity_cash')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only quantity" name="quantity_cash" id="quantity_cash" placeholder="Quantity" value="{{ old('quantity_cash') }}" tabindex="4">
                                                    @if(!empty($errors->first('quantity_cash')))
                                                        <p style="color: red;" >{{$errors->first('quantity_cash')}}</p>
                                                    @endif
                                                </div>
                                                <label for="rate" class="col-sm-1 control-label">x</label>
                                                <div class="col-sm-2 {{ !empty($errors->first('rate_cash')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only rate" name="rate_cash" id="rate_cash" placeholder="Rate" value="{{ old('rate_cash') }}" tabindex="5">
                                                    @if(!empty($errors->first('rate_cash')))
                                                        <p style="color: red;" >{{$errors->first('rate_cash')}}</p>
                                                    @endif
                                                </div>
                                                <label for="bill_amount" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('bill_amount_cash')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="bill_amount_cash" id="bill_amount_cash" placeholder="Bill amount" value="{{ !empty(old('bill_amount_cash')) ? old('bill_amount_cash') : '0' }}" readonly>
                                                    @if(!empty($errors->first('bill_amount_cash')))
                                                        <p style="color: red;" >{{$errors->first('bill_amount_cash')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="discount_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Discount :</label>
                                                <div class="col-sm-5 {{ !empty($errors->first('discount_cash')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only discount" name="discount_cash" id="discount_cash" placeholder="Discount" value="{{ !empty(old('discount_cash')) ? old('discount_cash') : '0' }}" tabindex="6">
                                                    @if(!empty($errors->first('discount_cash')))
                                                        <p style="color: red;" >{{$errors->first('discount_cash')}}</p>
                                                    @endif
                                                </div>
                                                <label for="deducted_total_cash" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('deducted_total_cash')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="deducted_total_cash" id="deducted_total_cash" placeholder="Deducted balance" value="{{ !empty(old('deducted_total_cash')) ? old('deducted_total_cash') : '0' }}" readonly>
                                                    @if(!empty($errors->first('deducted_total_cash')))
                                                        <p style="color: red;" >{{$errors->first('deducted_total_cash')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="old_balance" class="col-sm-2 control-label">Old Balance :</label>
                                                <div class="col-sm-5 {{ !empty($errors->first('old_balance')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only" name="old_balance" id="old_balance" placeholder="Old balance" value="{{ !empty(old('old_balance')) ? old('old_balance') : '0' }}" readonly>
                                                    @if(!empty($errors->first('old_balance')))
                                                        <p style="color: red;" >{{$errors->first('old_balance')}}</p>
                                                    @endif
                                                </div>
                                                <label for="total" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('total')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="total" id="total" placeholder="Total amount" value="{{ !empty(old('total')) ? old('total') : '0' }}" readonly>
                                                    @if(!empty($errors->first('total')))
                                                        <p style="color: red;" >{{$errors->first('total')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="paid_amount" class="col-sm-2 control-label"><b style="color: red;">* </b> Paid Amount :</label>
                                                <div class="col-sm-5 {{ !empty($errors->first('paid_amount')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only" name="paid_amount" id="paid_amount" placeholder="Paid amount" value="{{ old('paid_amount') }}" tabindex="7">
                                                    @if(!empty($errors->first('paid_amount')))
                                                        <p style="color: red;" >{{$errors->first('paid_amount')}}</p>
                                                    @endif
                                                </div>
                                                <label for="balance" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('balance')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="balance" id="balance" placeholder="Balance amount" value="{{ !empty(old('balance')) ? old('balance') : '0' }}" readonly>
                                                    <label id="balance_over_label"></label>
                                                    @if(!empty($errors->first('balance')))
                                                        <p style="color: red;" >{{$errors->first('balance')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"> </div><br>
                                    <div class="row">
                                        <div class="col-xs-3"></div>
                                        <div class="col-xs-3">
                                            <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="9">Clear</button>
                                        </div>
                                        {{-- <div class="col-sm-1"></div> --}}
                                        <div class="col-xs-3">
                                            <button type="submit" id="submit_button" class="btn btn-primary btn-block btn-flat" tabindex="8">Submit</button>
                                        </div>
                                        <!-- /.col -->
                                    </div><br>
                                </div>
                            </form>
                            <!-- /.form end -->
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            </div>
        </div>
        <div class="row no-print">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last 5 Sale Records</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($sales_records))
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Truck Number</th>
                                        <th>Purchaser</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales_records as $sales_record)
                                        <tr>
                                            <td>{{ $sales_record->date_time }}</td>
                                            <td>{{ $sales_record->vehicle->reg_number }}</td>
                                            <td>{{ $sales_record->transaction->debitAccount->account_name }}</td>
                                            <td>{{ $sales_record->product->name }}</td>
                                            @if($sales_record->measure_type == 3)
                                                <td>{{ $sales_record->quantity }} -Load</td>
                                            @elseif($sales_record->measure_type == 1)
                                                <td>{{ $sales_record->quantity }} -Cubic feet</td>
                                            @elseif($sales_record->measure_type == 2 && $sales_record->quantity != 0)
                                                <td>{{ $sales_record->quantity }} -Ton</td>
                                            @else
                                                <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="col-sm-5"></div>
                            <div class="col-sm-4">
                                <label>No sales records available to show!!</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="payment_with_sale_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Payment Confirmation</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Total Credit Amount<p class="pull-right">:</p></label>
                            <div class="col-sm-7">
                                <span id="modal_total_credit_amount"></span>
                            </div>
                        </div><br>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Paid Amount<p class="pull-right">:</p></label>
                            <div class="col-sm-7">
                                <span id="modal_payment"></span>
                            </div>
                        </div><br>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" id="modal_balance_over">Balance Amount<p class="pull-right">:</p></label>
                            <div class="col-sm-7">
                                <span id="modal_balance"></span>&emsp;&emsp;&emsp;<span id="modal_balance_icon"></span>
                            </div>
                        </div><br><br>
                        <div id="modal_warning">
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-10">
                                    <p style="color: red;"><b>Credits given by the 'user' are strictly under the responsibility of the 'user'.</b></p>
                                </div>
                                <div class="col-sm-1"><i id="modal_warning_more_button" class="fa fa-chevron-down"></i></div>
                            </div>
                            <div class="row" id="modal_warning_more" hidden>
                                <div class="col-sm-12">
                                    <p style="color: blue;">&emsp;The original bill amount will be debited to the sales account regardless of the cash payment. Credits issued by the user for cash transactions are maintained seperately and those are strictly under the responsibility of the user.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel & Edit Bill</button>
                        <button type="button" id="btn_cash_sale_modal_submit" class="btn btn-primary">Confirm Payment And Save Transaction</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/registration/saleRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection