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
                    <form action="{{route('excavator-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" tabindex="1">
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-2 control-label">Description : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product Description" style="resize: none;" tabindex="2">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product Description" style="resize: none;" tabindex="2"></textarea>
                                        @endif
                                        @if(!empty($errors->first('description')))
                                            <p style="color: red;" >{{$errors->first('description')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contractor_account_id" class="col-sm-2 control-label"><b style="color: red;">* </b> Contractor : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('contractor_account_id')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="contractor_account_id" id="contractor_account_id" tabindex="3">
                                            <option value="" {{ empty(old('contractor_account_id')) ? 'selected' : '' }}>Select contractor or provider account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ $account->id == old('contractor_account_id') ? 'selected' : '' }}>{{ $account->account_name }} - ({{ $account->relation }} account)</option>
                                            @endforeach
                                        </select>
                                        @if(!empty($errors->first('contractor_account_id')))
                                            <p style="color: red;" >{{ $errors->first('contractor_account_id')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rent_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Rental Type : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rent_type')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="rent_type" id="rent_type" tabindex="4">
                                            <option value="" selected="">Select rental type</option>
                                            <option value="hourly">Hourly rent</option>
                                            <option value="monthly">Monthly rental</option>
                                        </select>
                                        @if(!empty($errors->first('rent_type')))
                                            <p style="color: red;" >{{$errors->first('rent_type')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rent/Month : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rate_monthly')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="rate_monthly" id="rate_monthly" placeholder="Rent per month" value="{{ old('rate_monthly') }}" tabindex="5">
                                        @if(!empty($errors->first('rate_monthly')))
                                            <p style="color: red;" >{{$errors->first('rate_monthly')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rent/Hour-Bucket : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rate_bucket')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="rate_bucket" id="rate_bucket" placeholder="Rent per hour for bucket" value="{{ old('rate_bucket') }}" tabindex="6">
                                        @if(!empty($errors->first('rate_bucket')))
                                            <p style="color: red;" >{{$errors->first('rate_bucket')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rent/Hour-Breaker : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rate_breaker')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="rate_breaker" id="rate_breaker" placeholder="Rent per hour for breaker" value="{{ old('rate_breaker') }}" tabindex="7">
                                        @if(!empty($errors->first('rate_breaker')))
                                            <p style="color: red;" >{{$errors->first('rate_breaker')}}</p>
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
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="8">Submit</button>
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