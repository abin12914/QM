@extends('layouts.app')
@section('title', 'Multiple Sale Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Multiple Sale
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Multiple Sale Registration</li>
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
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="float: left;">Multiple Sale Registration</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div><br>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('multiple-credit-sales-register-action')}}" id="credit_sale_form" method="post" class="form-horizontal">
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
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <input type="radio" checked>
                                                    </span>
                                                    <label class="form-control">Random time will be added</label>
                                                </div>
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
                                        <div class="box-header with-border"></div><br>
                                        <div class="form-group">
                                            <label for="quantity_credit" class="col-sm-2 control-label"><b style="color: red;">* </b> Quantity x Rate :</label>
                                            <div class="col-sm-2 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control decimal_number_only quantity" name="quantity" id="quantity_credit" placeholder="Number of Load" value="{{ old('quantity') }}" tabindex="4">
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
                                            <td>{{ $sales_record->quantity }} -{{ ($sales_record->measure_type == 3)? 'Load' : 'Cubic feet' }}</td>
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
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/registration/multipleSaleRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection