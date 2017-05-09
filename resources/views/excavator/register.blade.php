@extends('layouts.app')
@section('title', 'Excavator Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Excavator
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Excavator</a></li>
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
                        <h3 class="box-title" style="float: left;">Excavator Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('product-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="{{ !empty($errors->first('name')) ? 'form-group has-error' : 'form-group' }}">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" required>
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="{{ !empty($errors->first('description')) ? 'form-group has-error' : 'form-group' }}">
                                    <label for="description" class="col-sm-2 control-label">Description : </label>
                                    <div class="col-sm-10">
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product Description" style="resize: none;">
                                                {{ old('description') }}
                                            </textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product Description" style="resize: none;"></textarea>
                                        @endif
                                        @if(!empty($errors->first('description')))
                                            <p style="color: red;" >{{$errors->first('description')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="account_id" class="col-sm-2 control-label"><b style="color: red;">* </b> Owner : </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="account_id" id="account_id" required>
                                            <option value="" selected="">Select Account</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rent_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Rental Type : </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="rent_type" id="rent_type">
                                            <option value="" selected="">Select Type</option>
                                            <option value="hourly">Hourly Rent</option>
                                            <option value="monthly">Monthly Rental</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rent/Month : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="rate_monthly" id="rate_monthly" placeholder="Rate per month" value="{{ old('rate_monthly') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rate/Hour-Bucket : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="rate_bucket" id="rate_bucket" placeholder="Rate per hour for bucket" value="{{ old('rate_bucket') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rate/Hour-Breaker : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="rate_breaker" id="rate_breaker" placeholder="Rate per hour for breaker" value="{{ old('rate_breaker') }}">
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
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