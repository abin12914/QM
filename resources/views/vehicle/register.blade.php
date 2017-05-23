@extends('layouts.app')
@section('title', 'Truck Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Truck
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Truck</a></li>
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
                        <h3 class="box-title" style="float: left;">Truck Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('vehicle-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="vehicle_reg_number_state_code" class="col-sm-2 control-label"><b style="color: red;">* </b> Registration Number : </label>
                                    <div class="col-sm-10">
                                        <div class="col-sm-2 {{ !empty($errors->first('vehicle_reg_number_state_code')) ? 'has-error' : '' }}">
                                            <input type="text" name="vehicle_reg_number_state_code" class="form-control alpha_only" id="vehicle_reg_number_state_code" placeholder="" value="{{ !empty(old('vehicle_reg_number_state_code')) ? old('vehicle_reg_number_state_code') : 'KL' }}" tabindex="1" maxlength="2">
                                        </div>
                                        <div class="col-sm-2 {{ !empty($errors->first('vehicle_reg_number_region_code')) ? 'has-error' : '' }}">
                                            <input type="text" name="vehicle_reg_number_region_code" class="form-control number_only" id="vehicle_reg_number_region_code" placeholder="" value="{{ old('vehicle_reg_number_region_code') }}" tabindex="2" maxlength="2">
                                        </div>
                                        <div class="col-sm-2 {{ !empty($errors->first('vehicle_reg_number_unique_alphabet')) ? 'has-error' : '' }}">
                                            <input type="text" name="vehicle_reg_number_unique_alphabet" class="form-control alpha_only" id="vehicle_reg_number_unique_alphabet" placeholder="" value="{{ old('vehicle_reg_number_unique_alphabet') }}" tabindex="3" maxlength="2">
                                        </div>
                                        <div class="col-sm-2 {{ !empty($errors->first('vehicle_reg_number_unique_digit')) ? 'has-error' : '' }}">
                                            <input type="text" name="vehicle_reg_number_unique_digit" class="form-control number_only" id="vehicle_reg_number_unique_digit" placeholder="" value="{{ old('vehicle_reg_number_unique_digit') }}" tabindex="4" maxlength="4">
                                        </div>
                                        <div class="col-sm-4 {{ !empty($errors->first('vehicle_reg_number')) ? 'has-error' : '' }}">
                                            <input type="text" name="vehicle_reg_number" class="form-control" id="vehicle_reg_number" value="{{ old('vehicle_reg_number') }}" readonly="">
                                        </div>
                                    </div>
                                    @if(!empty($errors->first('vehicle_reg_number')))
                                        <p style="color: red;" >{{$errors->first('vehicle_reg_number')}}</p>
                                    @elseif(!empty($errors->first('vehicle_reg_number_state_code')))
                                        <p style="color: red;" >{{$errors->first('vehicle_reg_number_state_code')}}</p>
                                    @elseif(!empty($errors->first('vehicle_reg_number_region_code')))
                                        <p style="color: red;" >{{$errors->first('vehicle_reg_number_region_code')}}</p>
                                    @elseif(!empty($errors->first('vehicle_reg_number_unique_alphabet')))
                                        <p style="color: red;" >{{$errors->first('vehicle_reg_number_unique_alphabet')}}</p>
                                    @elseif(!empty($errors->first('vehicle_reg_number_unique_digit')))
                                        <p style="color: red;" >{{$errors->first('vehicle_reg_number_unique_digit')}}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-2 control-label">Description : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Vehicle Description" style="resize: none;" tabindex="5">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Vehicle Description" style="resize: none;" tabindex="5"></textarea>
                                        @endif
                                        @if(!empty($errors->first('description')))
                                            <p style="color: red;" >{{$errors->first('description')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="owner_name" class="col-sm-2 control-label">Owner : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('owner_name')) ? 'has-error' : '' }}">
                                        <input type="text" name="owner_name" class="form-control" id="owner_name" placeholder="Registered owner name" value="{{ old('owner_name') }}" tabindex="6">
                                        @if(!empty($errors->first('owner_name')))
                                            <p style="color: red;" >{{$errors->first('owner_name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="vehicle_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Vehicle Type : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('vehicle_type')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="vehicle_type" id="vehicle_type" tabindex="7">
                                            <option value="" {{ empty(old('vehicle_type')) ? 'selected' : '' }}selected>Select vehicle type</option>
                                            @foreach($vehicleTypes as $vehicleType)
                                                <option value="{{ $vehicleType->id }}" {{ (old('vehicle_type') == $vehicleType->id) ? 'selected' : '' }}>{{ $vehicleType->name }} - {{ $vehicleType->generic_quantity }} cubic unit class</option>
                                            @endforeach
                                        </select>
                                        @if(!empty($errors->first('vehicle_type')))
                                            <p style="color: red;" >{{$errors->first('vehicle_type')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Volume In Feet : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('volume')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control number_only" name="volume" id="volume" placeholder="Volume in cubic feet" value="{{ old('volume') }}" tabindex="8" maxlength="4">
                                        @if(!empty($errors->first('volume')))
                                            <p style="color: red;" >{{$errors->first('volume')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="body_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Body Type : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('body_type')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="body_type" id="body_type" tabindex="9">
                                            <option value="" {{ empty(old('body_type')) ? 'selected' : '' }}>Select body type</option>
                                            <option value="level" {{ (old('body_type') == 'level') ? 'selected' : '' }}>Level</option>
                                            <option value="extra-1" {{ (old('body_type') == 'extra-1') ? 'selected' : '' }}>Extended Body</option>
                                            <option value="extra-2" {{ (old('body_type') == 'extra-2') ? 'selected' : '' }}>Extra Extended Body</option>
                                        </select>
                                        @if(!empty($errors->first('body_type')))
                                            <p style="color: red;" >{{$errors->first('body_type')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="11">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="10">Submit</button>
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