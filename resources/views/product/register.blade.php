@extends('layouts.app')
@section('title', 'Product Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Product
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Product Registration</li>
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
                        <h3 class="box-title" style="float: left;">Product Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('product-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Product Name : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Product name" value="{{ old('name') }}" tabindex="1">
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-2 control-label">Description : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product description" style="resize: none;" tabindex="2">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product description" style="resize: none;" tabindex="2"></textarea>
                                        @endif
                                        @if(!empty($errors->first('description')))
                                            <p style="color: red;" >{{$errors->first('description')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rate/Cubic feet : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rate_feet')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="rate_feet" id="rate_feet" placeholder="Rate per cubic feet" value="{{ old('rate_feet') }}" tabindex="3">
                                        @if(!empty($errors->first('rate_feet')))
                                            <p style="color: red;" >{{$errors->first('rate_feet')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Rate/Metric ton : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rate_ton')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="rate_ton" id="rate_ton" placeholder="Rate per metric ton" value="{{ old('rate_ton') }}" tabindex="4">
                                        @if(!empty($errors->first('rate_ton')))
                                            <p style="color: red;" >{{$errors->first('rate_ton')}}</p>
                                        @endif
                                    </div>
                                </div>
                                @if(!empty($vehicleTypes))
                                    <div class="box-header with-border">
                                        <h3 class="box-title" style="float: left;">Royalty Details</h3>
                                    </div><br>
                                    @foreach($vehicleTypes as $vehicleType)
                                        <div class="form-group">
                                            <label for="royalty_{{ $vehicleType->id }}" class="col-sm-2 control-label"><b style="color: red;">* </b> {{ $vehicleType->name}}: </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('royalty.'.$vehicleType->id)) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control decimal_number_only" name="royalty[{{ $vehicleType->id }}]" id="royalty_{{ $vehicleType->id }}" placeholder="Royalty amount for {{ $vehicleType->name }}" value="{{ old('royalty.'.$vehicleType->id) }}" tabindex="4">
                                            @if(!empty($errors->first('royalty.'.$vehicleType->id)))
                                                <p style="color: red;" >{{$errors->first('royalty.'.$vehicleType->id)}}</p>
                                            @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="6">Clear</button>
                                </div>
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="5">Submit</button>
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