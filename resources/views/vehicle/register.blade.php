@extends('layouts.app')
@section('title', 'Excavator Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Vehicle
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Vehicle</a></li>
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
                        <h3 class="box-title" style="float: left;">Vehicle Registration</h3>
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
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Registration Number : </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="reg_number" class="form-control" id="reg_number" placeholder="Registartion number" value="{{ old('reg_number') }}" required>
                                        @if(!empty($errors->first('reg_number')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="{{ !empty($errors->first('description')) ? 'form-group has-error' : 'form-group' }}">
                                    <label for="description" class="col-sm-2 control-label">Description : </label>
                                    <div class="col-sm-10">
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Vehicle Description" style="resize: none;">
                                                {{ old('description') }}
                                            </textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Vehicle Description" style="resize: none;"></textarea>
                                        @endif
                                        @if(!empty($errors->first('description')))
                                            <p style="color: red;" >{{$errors->first('description')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="vehicle_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Vehicle Type : </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="vehicle_type" id="vehicle_type" required>
                                            <option value="" selected="">Select type</option>
                                        </select>
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
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Volume In Feet : </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="volume" id="volume" placeholder="Volume in cubic feet" value="{{ old('volume') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="body_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Body Type : </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="body_type" id="body_type" required>
                                            <option value="" selected="">Select body type</option>
                                            <option value="level">Level</option>
                                            <option value="extra-1">Extended Body</option>
                                            <option value="extra-2">Extra Extended Body</option>
                                        </select>
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