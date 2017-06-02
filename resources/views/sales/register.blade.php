@extends('layouts.app')
@section('title', 'Sales Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sales
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Sales</a></li>
            <li class="active">Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {{ Session::get('message') }}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <!-- nav-tabs-custom -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#credit_sale_tab" data-toggle="tab">Credit Sale</a></li>
                        <li><a href="#cash_sale_tab" data-toggle="tab">Cash Sale</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="credit_sale_tab">
                            <!-- form start -->
                            <form action="{{route('sales-register-action')}}" id="credit_sale_form" method="post" class="form-horizontal" multipart-form-data>
                                <div class="box-body">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="vehicle_number_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Truck Number : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('vehicle_number')) ? 'has-error' : '' }}">
                                                    <select name="vehicle_number" class="form-control vehicle_number" id="vehicle_number_credit" tabindex="1" style="width: 100%">
                                                        <option value="">Select truck number</option>
                                                        @foreach($vehicles as $vehicle)
                                                            <option value="{{ $vehicle->id }}" {{ (old('vehicle_number') == $vehicle->id) ? 'selected' : '' }} data-volume="{{ $vehicle->volume }}" data-bodytype="{{ $vehicle->body_type }}">{{ $vehicle->reg_number }} - {{ $vehicle->owner_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('vehicle_number')))
                                                        <p style="color: red;" >{{$errors->first('vehicle_number')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="purchaser_credit" class="col-sm-2 control-label">Purchaser : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('purchaser')) ? 'has-error' : '' }}">
                                                    <select name="purchaser" class="form-control purchaser" id="purchaser_credit" tabindex="2" style="width: 100%">
                                                        <option value="" {{ empty(old('purchaser')) ? 'selected' : '' }}>Select purchaser</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}" {{ (old('purchaser') == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('purchaser')))
                                                        <p style="color: red;" >{{$errors->first('purchaser')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="date_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                                <div class="col-sm-5 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date_credit" placeholder="Date" value="{{ old('date') }}" tabindex="22">
                                                </div>
                                                <div class="col-sm-5 {{ !empty($errors->first('time')) ? 'has-error' : '' }}">
                                                    <div class="bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker" name="time" id="time_credit" placeholder="Time" value="{{ old('time') }}" tabindex="23">
                                                    </div>
                                                    @if(!empty($errors->first('time')))
                                                        <p style="color: red;" >{{$errors->first('time')}}</p>
                                                    @elseif(!empty($errors->first('date')))
                                                        <p style="color: red;" >{{$errors->first('date')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="product_credit" class="col-sm-2 control-label">Product : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('product')) ? 'has-error' : '' }}">
                                                    <select name="product" class="form-control product" id="product_credit" tabindex="3" style="width: 100%">
                                                        <option value="" {{ empty(old('product')) ? 'selected' : '' }}>Select product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ (old('product') == $product->id) ? 'selected' : '' }} data-rate-feet="{{ $product->rate_feet }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('product')))
                                                        <p style="color: red;" >{{$errors->first('product')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="measure_type_volume_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Measure Type : </label>
                                                <div class="col-lg-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="measure_type" class="measure_type" id="measure_type_volume_credit" value="volume" checked="">
                                                        </span>
                                                        <label for="measure_type_volume_credit" class="form-control" tabindex="20">Volume</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="measure_type" class="measure_type" id="measure_type_weighment_credit" value="weighment">
                                                        </span>
                                                        <label for="measure_type_weighment_credit" class="form-control" tabindex="21">Weighment</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="measure_volume_details">
                                                <div class="box-header with-border"></div><br>
                                                <div class="form-group">
                                                    <label for="quantity_credit" class="col-sm-2 control-label">Quantity * Rate :</label>
                                                    <div class="col-sm-2 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control decimal_number_only quantity" name="quantity" id="quantity_credit" placeholder="Quantity" value="{{ old('quantity') }}" tabindex="4" tooltip>
                                                    </div>
                                                    <label for="rate_credit" class="col-sm-1 control-label">x</label>
                                                    <div class="col-sm-2 {{ !empty($errors->first('rate')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control decimal_number_only rate" name="rate" id="rate_credit" placeholder="Rate" value="{{ old('rate') }}" tabindex="5">
                                                    </div>
                                                    <label for="bill_amount_credit" class="col-sm-1 control-label">=</label>
                                                    <div class="col-sm-4 {{ !empty($errors->first('bill_amount')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control" name="bill_amount" id="bill_amount_credit" placeholder="Bill amount" value="{{ old('bill_amount') }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="discount_credit" class="col-sm-2 control-label">Discount :</label>
                                                    <div class="col-sm-5 {{ !empty($errors->first('discount')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control decimal_number_only" name="discount" id="discount_credit" placeholder="Discount" value="{{ old('discount') }}" tabindex="6">
                                                    </div>
                                                    <label for="deducted_total_credit" class="col-sm-1 control-label">=</label>
                                                    <div class="col-sm-4 {{ !empty($errors->first('deducted_total')) ? 'has-error' : '' }}">
                                                        <input type="text" class="form-control" name="deducted_total" id="deducted_total_credit" placeholder="Deducted balance" value="{{ old('deducted_total') }}" readonly>
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
                        <div class="tab-pane" id="cash_sale_tab">
                            <!-- form start -->
                            <form action="{{route('sales-register-action')}}" id="cash_sale_form" method="post" class="form-horizontal" multipart-form-data>
                                <div class="box-body">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="vehicle_number_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Truck Number : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('vehicle_number')) ? 'has-error' : '' }}">
                                                    <select name="vehicle_number" class="form-control vehicle_number" id="vehicle_number_cash" tabindex="1" style="width: 100%">
                                                        <option value="">Select truck number</option>
                                                        @foreach($vehicles as $vehicle)
                                                            <option value="{{ $vehicle->id }}" {{ (old('vehicle_number') == $vehicle->id) ? 'selected' : '' }} data-volume="{{ $vehicle->volume }}" data-bodytype="{{ $vehicle->body_type }}">{{ $vehicle->reg_number }} - {{ $vehicle->owner_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('vehicle_number')))
                                                        <p style="color: red;" >{{$errors->first('vehicle_number')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="purchaser_cash" class="col-sm-2 control-label">Purchaser : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('purchaser')) ? 'has-error' : '' }}">
                                                    <select name="purchaser" class="form-control purchaser" id="purchaser_cash" tabindex="2" style="width: 100%">
                                                        <option value="" {{ empty(old('purchaser')) ? 'selected' : '' }}>Select purchaser</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}" {{ (old('purchaser') == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('purchaser')))
                                                        <p style="color: red;" >{{$errors->first('purchaser')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="date_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                                <div class="col-sm-5 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date_cash" placeholder="Date" value="{{ old('date') }}" tabindex="22">
                                                </div>
                                                <div class="col-sm-5 {{ !empty($errors->first('time')) ? 'has-error' : '' }}">
                                                    <div class="bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker" name="time" id="time_cash" placeholder="Time" value="{{ old('time') }}" tabindex="23">
                                                    </div>
                                                    @if(!empty($errors->first('time')))
                                                        <p style="color: red;" >{{$errors->first('time')}}</p>
                                                    @elseif(!empty($errors->first('date')))
                                                        <p style="color: red;" >{{$errors->first('date')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="product_cash" class="col-sm-2 control-label">Product : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('product')) ? 'has-error' : '' }}">
                                                    <select name="product" class="form-control product" id="product_cash" tabindex="3" style="width: 100%">
                                                        <option value="" {{ empty(old('purchaser')) ? 'selected' : '' }}>Select product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ (old('product') == $product->id) ? 'selected' : '' }} data-rate-feet="{{ $product->rate_feet }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(!empty($errors->first('product')))
                                                        <p style="color: red;" >{{$errors->first('product')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="box-header with-border"></div><br>
                                            <div class="form-group">
                                                <label for="quantity_cash" class="col-sm-2 control-label">Quantity * Rate :</label>
                                                <div class="col-sm-2 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only quantity" name="quantity" id="quantity_cash" placeholder="Quantity" value="{{ old('quantity') }}" tabindex="4">
                                                </div>
                                                <label for="rate" class="col-sm-1 control-label">x</label>
                                                <div class="col-sm-2 {{ !empty($errors->first('rate')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only rate" name="rate" id="rate_cash" placeholder="Rate" value="{{ old('rate') }}" tabindex="5">
                                                </div>
                                                <label for="bill_amount" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('bill_amount')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="bill_amount" id="bill_amount_cash" placeholder="Bill amount" value="{{ old('bill_amount') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="discount_cash" class="col-sm-2 control-label">Discount :</label>
                                                <div class="col-sm-5 {{ !empty($errors->first('discount')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only" name="discount" id="discount_cash" placeholder="Discount" value="{{ old('discount') }}" tabindex="6">
                                                </div>
                                                <label for="deducted_total_cash" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('deducted_total')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="deducted_total" id="deducted_total_cash" placeholder="Deducted balance" value="{{ old('deducted_total') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="old_balance" class="col-sm-2 control-label">Old Balance :</label>
                                                <div class="col-sm-5 {{ !empty($errors->first('old_balance')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only" name="old_balance" id="old_balance" placeholder="Old balance" value="{{ old('old_balance') }}" readonly>
                                                </div>
                                                <label for="total" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('total')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="total" id="total" placeholder="Total amount" value="{{ old('total') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="paid_amount" class="col-sm-2 control-label">Paid Amount :</label>
                                                <div class="col-sm-5 {{ !empty($errors->first('paid_amount')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only" name="paid_amount" id="paid_amount" placeholder="Paid amount" value="{{ old('paid_amount') }}" tabindex="7">
                                                </div>
                                                <label for="balance" class="col-sm-1 control-label">=</label>
                                                <div class="col-sm-4 {{ !empty($errors->first('balance')) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control" name="balance" id="balance" placeholder="Balance amount" value="{{ old('balance') }}" readonly>
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
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last 5 Sales Records</h3>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales_records as $sales_record)
                                        <tr>
                                            <td>{{ $sales_record->date_time }}</td>
                                            <td>{{ $sales_record->truck_number }}</td>
                                            <td>{{ $sales_record->purchaser }}</td>
                                            <td>{{ $sales_record->product }}</td>
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
                            <label class="col-sm-4 control-label">Total Credit Amount :</label>
                            <div class="col-sm-7">
                                <span id="modal_total_credit_amount"></span>
                            </div>
                        </div><br>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Paid Amount :</label>
                            <div class="col-sm-7">
                                <span id="modal_payment"></span>
                            </div>
                        </div><br>
                        <div class="form-group">
                            <label class="col-sm-4 control-label modal_balance_over">Balance Amount :</label>
                            <div class="col-sm-7">
                                <span id="modal_balance"></span>
                            </div>
                        </div><br><br>
                        <div id="modal_warning">
                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <p style="color: red;"><b>Balance amount handling responsibility is allotted to the operator.</b></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p style="color: blue;">&emsp;The original bill amount will be debited to the sales account regardless of the cash payment. <b class="modal_balance_over">Balance amount</b> is <b class="modal_debit_credit">credited from</b> a temporary account until the next day closing. If the amount did not cleared with in this time period, then it would be <b class="modal_debit_credit">credited from</b> the operator account;</p>
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
    <script src="/js/saleRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection