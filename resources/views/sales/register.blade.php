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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="float: left;">Sales Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('account-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <label for="vehicle_number" class="col-sm-2 control-label"><b style="color: red;">* </b> Truck Number : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('vehicle_number')) ? 'has-error' : '' }}">
                                            <select name="vehicle_number" class="form-control select2" id="vehicle_number" tabindex="1" style="width: 100%">
                                                <option value="">Select truck number</option>
                                                @foreach($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->reg_number }}">{{ $vehicle->reg_number }} - {{ $vehicle->owner_name }}</option>
                                                @endforeach
                                            </select>
                                            @if(!empty($errors->first('vehicle_number')))
                                                <p style="color: red;" >{{$errors->first('vehicle_number')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle_number" class="col-sm-2 control-label"><b style="color: red;">* </b> Sale Type : </label>
                                        <div class="col-lg-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="sale_type" id="sale_type_credit" value="credit" checked="">
                                                </span>
                                                <label for="sale_type_credit" class="form-control" tabindex="20">Credit</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="sale_type" id="sale_type_cash" value="cash">
                                                </span>
                                                <label for="sale_type_cash" class="form-control" tabindex="21">Cash</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="purchaser" class="col-sm-2 control-label">Purchaser : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('purchaser')) ? 'has-error' : '' }}">
                                            <select name="purchaser" class="form-control select2" id="purchaser" tabindex="2" style="width: 100%">
                                                <option value="">Select purchaser</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                                @endforeach
                                            </select>
                                            @if(!empty($errors->first('purchaser')))
                                                <p style="color: red;" >{{$errors->first('purchaser')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="date" class="col-sm-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                        <div class="col-sm-5 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="date" id="datepicker" placeholder="Date" value="{{ old('date') }}" tabindex="22">
                                        </div>
                                        <div class="col-sm-5 {{ !empty($errors->first('time')) ? 'has-error' : '' }}">
                                            <div class="bootstrap-timepicker">
                                                <input type="text" class="form-control timepicker" name="time" id="time" placeholder="Time" value="{{ old('time') }}" tabindex="23">
                                            </div>
                                            @if(!empty($errors->first('time')))
                                                <p style="color: red;" >{{$errors->first('time')}}</p>
                                            @elseif(!empty($errors->first('date')))
                                                <p style="color: red;" >{{$errors->first('date')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product" class="col-sm-2 control-label">Product : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('product')) ? 'has-error' : '' }}">
                                            <select name="product" class="form-control select2" id="product" tabindex="3" style="width: 100%">
                                                <option value="">Select product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            @if(!empty($errors->first('product')))
                                                <p style="color: red;" >{{$errors->first('product')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="measure_type_volume" class="col-sm-2 control-label"><b style="color: red;">* </b> Measure Type : </label>
                                        <div class="col-lg-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="measure_type" id="measure_type_volume" value="volume" checked="">
                                                </span>
                                                <label for="measure_type_volume" class="form-control" tabindex="20">Volume</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="measure_type" id="measure_type_weighment" value="weighment">
                                                </span>
                                                <label for="measure_type_weighment" class="form-control" tabindex="21">Weighment</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity" class="col-sm-2 control-label">Amount : </label>
                                        <div class="col-sm-2 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="quantity" id="quantity" placeholder="Quantity" value="{{ old('quantity') }}" ="" tabindex="4">
                                        </div>
                                        <label for="rate" class="col-sm-1 control-label">x</label>
                                        <div class="col-sm-2 {{ !empty($errors->first('rate')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="rate" id="rate" placeholder="Rate" value="{{ old('rate') }}" ="" tabindex="5">
                                        </div>
                                        <label for="amount" class="col-sm-1 control-label">=</label>
                                        <div class="col-sm-4 {{ !empty($errors->first('amount')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Amount" value="{{ old('amount') }}" ="" tabindex="6">
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
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="7">Submit</button>
                                </div>
                                <!-- /.col -->
                            </div><br>
                        </div>
                    </form>
                </div>
                <!-- /.box primary -->
            </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
{{-- @section('scripts')
@endsection --}}
{{-- <script type="text/javascript">
    vehicleRegLink = '{{ route('vehicle-register-view') }}';
</script> --}}