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
                                        <label for="vehicle_number" class="col-sm-2 control-label"><b style="color: red;">* </b> Vehicle Number : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('vehicle_number')) ? 'has-error' : '' }}">
                                            <select name="vehicle_number" class="form-control select2" id="vehicle_number" tabindex="1" style="width: 100%">
                                                
                                            </select>
                                            @if(!empty($errors->first('vehicle_number')))
                                                <p style="color: red;" >{{$errors->first('vehicle_number')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="purchaser" class="col-sm-2 control-label">Purchaser : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('purchaser')) ? 'has-error' : '' }}">
                                            <select name="purchaser" class="form-control select2" id="purchaser" tabindex="3" style="width: 100%">
                                                
                                            </select>
                                            @if(!empty($errors->first('purchaser')))
                                                <p style="color: red;" >{{$errors->first('purchaser')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="date" class="col-sm-2 control-label"><b style="color: red;">* </b> Date and Time : </label>
                                        <div class="col-sm-5 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="date" id="datepicker" placeholder="Date" value="{{ old('date') }}" ="" tabindex="10">
                                            @if(!empty($errors->first('date')))
                                                <p style="color: red;" >{{$errors->first('date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-5 {{ !empty($errors->first('time')) ? 'has-error' : '' }}">
                                            <div class="bootstrap-timepicker">
                                                <input type="text" class="form-control timepicker" name="time" id="time" placeholder="Time" value="{{ old('time') }}" ="" tabindex="10">
                                            </div>
                                            @if(!empty($errors->first('time')))
                                                <p style="color: red;" >{{$errors->first('time')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product" class="col-sm-2 control-label">Product : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('product')) ? 'has-error' : '' }}">
                                            <select name="product" class="form-control select2" id="product" tabindex="2" style="width: 100%">
                                                
                                            </select>
                                            @if(!empty($errors->first('product')))
                                                <p style="color: red;" >{{$errors->first('product')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity" class="col-sm-2 control-label">Amount : </label>
                                        <div class="col-sm-2 {{ !empty($errors->first('quantity')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="quantity" id="quantity" placeholder="Quantity" value="{{ old('quantity') }}" ="" tabindex="10">
                                        </div>
                                        <div class="col-sm-2 {{ !empty($errors->first('rate')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="rate" id="rate" placeholder="Rate" value="{{ old('rate') }}" ="" tabindex="10">
                                        </div>
                                        <div class="col-sm-6 {{ !empty($errors->first('amount')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Amount" value="{{ old('amount') }}" ="" tabindex="10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="12">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="11">Submit</button>
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
@section('scripts')
    <!-- select2 -->
    <script src="/js/plugins/select2/select2.min.js"></script>
    <!-- Timepicker -->
    <script src="/js/plugins/timepicker/bootstrap-timepicker.min.js"></script>
@endsection