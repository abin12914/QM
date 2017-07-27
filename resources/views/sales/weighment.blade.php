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
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="float: left;">Weighment Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- form start -->
                        <form action="{{route('sales-weighment-register-action')}}" id="sale_weighment_registration_form" method="post" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    {{-- <div class="col-md-1"></div> --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="vehicle_number_credit" class="col-md-2 control-label"><b style="color: red;">* </b> Truck Number : </label>
                                            <div class="col-sm-4 {{ !empty($errors->first('vehicle_id')) ? 'has-error' : '' }}">
                                                <input type="text" name="vehicle_id" class="form-control vehicle_number" id="vehicle_number_credit" tabindex="1" style="width: 100%">
                                                @if(!empty($errors->first('vehicle_id')))
                                                    <p style="color: red;" >{{$errors->first('vehicle_id')}}</p>
                                                @endif
                                            </div>
                                            <label for="product_credit" class="col-md-1 control-label"><b style="color: red;">* </b> Product : </label>
                                            <div class="col-sm-4 {{ !empty($errors->first('product_id')) ? 'has-error' : '' }}">
                                                <input type="text" name="product_id" class="form-control product" id="product_credit" tabindex="3" style="width: 100%">
                                                @if(!empty($errors->first('product_id')))
                                                    <p style="color: red;" >{{$errors->first('product_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_credit" class="col-md-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                            <div class="col-sm-4 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control decimal_number_only" name="date" id="date_credit" placeholder="Date" value="{{ old('date') }}" tabindex="22">
                                                @if(!empty($errors->first('date')))
                                                    <p style="color: red;" >{{$errors->first('date')}}</p>
                                                @endif
                                            </div>
                                            <label for="purchaser_credit" class="col-md-1 control-label"><b style="color: red;">* </b> Purchaser : </label>
                                            <div class="col-sm-4 {{ !empty($errors->first('purchaser_account_id')) ? 'has-error' : '' }}">
                                                <input type="text" name="purchaser_account_id" class="form-control purchaser" id="purchaser_credit" tabindex="2" style="width: 100%">
                                                @if(!empty($errors->first('purchaser_account_id')))
                                                    <p style="color: red;" >{{$errors->first('purchaser_account_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="measure_volume_details">
                                            <div class="box-header with-border"></div>
                                            <div class="form-group">
                                                <div class="col-sm-1"></div>
                                                <div class="col-sm-3 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                                    <label for="quantity_credit" class="control-label"><b style="color: red;">* </b> Quantity :</label>
                                                    <input type="text" class="form-control decimal_number_only quantity" name="quantity" id="quantity_credit" placeholder="Quantity" value="{{ old('quantity') }}" tabindex="4">
                                                    @if(!empty($errors->first('quantity')))
                                                        <p style="color: red;" >{{$errors->first('quantity')}}</p>
                                                    @endif
                                                </div>
                                                {{-- <label for="rate_credit" class="col-sm-1 control-label">x</label> --}}
                                                <div class="col-sm-3 {{ !empty($errors->first('rate')) ? 'has-error' : '' }}">
                                                    <label for="quantity_credit" class="control-label"><b style="color: red;">* </b> Rate :</label>
                                                    <input type="text" class="form-control decimal_number_only rate" name="rate" id="rate_credit" placeholder="Rate" value="{{ old('rate') }}" tabindex="5">
                                                    @if(!empty($errors->first('rate')))
                                                        <p style="color: red;" >{{$errors->first('rate')}}</p>
                                                    @endif
                                                </div>
                                                {{-- <label for="bill_amount_credit" class="col-sm-1 control-label">=</label> --}}
                                                <div class="col-sm-4 {{ !empty($errors->first('bill_amount')) ? 'has-error' : '' }}">
                                                    <label for="quantity_credit" class="control-label"><b style="color: red;">* </b> Total :</label>
                                                    <input type="text" class="form-control" name="bill_amount" id="bill_amount_credit" placeholder="Bill amount" value="{{ !empty(old('bill_amount')) ? old('bill_amount') : '0' }}" readonly>
                                                    @if(!empty($errors->first('bill_amount')))
                                                        <p style="color: red;" >{{$errors->first('bill_amount')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-1"></div>
                                                <div class="col-sm-6 {{ !empty($errors->first('discount')) ? 'has-error' : '' }}">
                                                    <label for="discount_credit" class="control-label"><b style="color: red;">* </b> Discount :</label>
                                                    <input type="text" class="form-control decimal_number_only discount" name="discount" id="discount_credit" placeholder="Discount" value="{{ !empty(old('discount')) ? old('discount') : '0' }}" tabindex="6">
                                                    @if(!empty($errors->first('discount')))
                                                        <p style="color: red;" >{{$errors->first('discount')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-sm-4 {{ !empty($errors->first('deducted_total')) ? 'has-error' : '' }}">
                                                    <label for="deducted_total_credit" class="control-label">Bill Amount</label>
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
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Cancel</button>
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
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pending Records</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($sales))
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date & Time</th>
                                        <th>Truck Number</th>
                                        <th>Purchaser</th>
                                        <th>Product</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $index => $sales_record)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $sales_record->date_time }}</td>
                                            <td>{{ $sales_record->vehicle->reg_number }}</td>
                                            <td>{{ $sales_record->transaction->debitAccount->account_name }}</td>
                                            <td>{{ $sales_record->product->name }}</td>
                                            <td><button type="button"><div class="external-event bg-aqua">Add</div></button></td>
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
                                    <p style="color: red;"><b>Credits given by the user are strictly under the responsibility of the user.</b></p>
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
    <script src="/js/saleRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection