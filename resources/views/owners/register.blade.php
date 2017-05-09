@extends('layouts.app')
@section('title', 'Owner Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Owner
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Owner</a></li>
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
                            <h3 class="box-title" style="float: left;">Owner Registration</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="" method="post" class="form-horizontal" multipart-form-data>
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
                                        <div class="{{ !empty($errors->first('email')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="email" class="col-sm-2 control-label">E-mail : </label>
                                            <div class="col-sm-10">
                                                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" value="{{ old('email') }}" required>
                                                @if(!empty($errors->first('email')))
                                                    <p style="color: red;" >{{$errors->first('email')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="{{ !empty($errors->first('phone')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="phone" class="col-sm-2 control-label"><b style="color: red;">* </b> Phone : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number" value="{{ old('phone') }}" required>
                                                @if(!empty($errors->first('phone')))
                                                    <p style="color: red;" >{{$errors->first('phone')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="{{ !empty($errors->first('address')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="address" class="col-sm-2 control-label">Address : </label>
                                            <div class="col-sm-10">
                                                @if(!empty(old('address')))
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;">
                                                        {{ old('address') }}
                                                    </textarea>
                                                @else
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;"></textarea>
                                                @endif
                                                @if(!empty($errors->first('address')))
                                                    <p style="color: red;" >{{$errors->first('address')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="{{ !empty($errors->first('image_file')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="image_file" class="col-sm-2 control-label">Image : </label>
                                            <div class="col-sm-10">
                                                <input type="file" name="image_file" class="form-control" id="image_file">
                                                @if(!empty($errors->first('image_file')))
                                                    <p style="color: red;" >{{$errors->first('image_file')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="{{ !empty($errors->first('user_name')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="user_name" class="col-sm-2 control-label"><b style="color: red;">* </b> User Name : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="user_name" class="form-control" placeholder="User Name" value="{{ old('user_name') }}" tabindex="2" required>
                                                @if(!empty($errors->first('user_name')))
                                                    <p style="color: red;" >{{$errors->first('user_name')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="{{ !empty($errors->first('valid_till')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="valid_till" class="col-sm-2 control-label">User Validity : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="valid_till" class="form-control" placeholder="Keep this field empty for unlimited user validity." value="{{ !empty(old('valid_till')) ? old('valid_till') : '' }}" id="datepicker" tabindex="7">
                                                @if(!empty($errors->first('valid_till')))
                                                    <p style="color: red;" >{{$errors->first('valid_till')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="{{ !empty($errors->first('password')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="password" class="col-sm-2 control-label"><b style="color: red;">* </b> Password : </label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password" class="form-control" placeholder="Password" required tabindex="8">
                                                @if(!empty($errors->first('password')))
                                                    <p style="color: red;" >{{$errors->first('password')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-sm-2 control-label"><b style="color: red;">* </b> Confirm Password : </label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required tabindex="9">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="financial_status" id="financial_status">
                                                    <option value="" selected="">Select Status</option>
                                                    <option value="none">None (No pending transactions)</option>
                                                    <option value="debit">Debitor (Account Holder Owe Company)</option>
                                                    <option value="credit">Creditor (Company Owe Account Holder)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><b style="color: red;">* </b> Opening Balance: </label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="{{ old('opening_balance') }}">
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