@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            User
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> User</a></li>
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
                            <h3 class="box-title" style="float: left;">User Registration</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <form action="{{route('user-register-action')}}" method="post" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="{{ !empty($errors->first('name')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" tabindex="1" required>
                                            </div>
                                            @if(!empty($errors->first('name')))
                                                <p style="color: red;" >{{$errors->first('name')}}</p>
                                            @endif
                                        </div>
                                        <div class="{{ !empty($errors->first('user_name')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="user_name" class="col-sm-2 control-label"><b style="color: red;">* </b> User Name : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="user_name" class="form-control" placeholder="User Name" value="{{ old('user_name') }}" tabindex="2" required>
                                            </div>
                                            @if(!empty($errors->first('user_name')))
                                                <p style="color: red;" >{{$errors->first('user_name')}}</p>
                                            @endif
                                        </div>
                                        <div class="{{ !empty($errors->first('email')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="email" class="col-sm-2 control-label">E-mail : </label>
                                            <div class="col-sm-10">
                                                <input type="email" name="email" class="form-control" placeholder="E-Mail" value="{{ !empty(old('email')) ? old('email') : '' }}" tabindex="3">
                                            </div>
                                            @if(!empty($errors->first('email')))
                                                <p style="color: red;" >{{$errors->first('email')}}</p>
                                            @endif
                                        </div>
                                        <div class="{{ !empty($errors->first('phone')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="phone" class="col-sm-2 control-label"><b style="color: red;">* </b> Phone : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone') }}" required tabindex="4">
                                            </div>
                                            @if(!empty($errors->first('phone')))
                                                <p style="color: red;" >{{$errors->first('phone')}}</p>
                                            @endif
                                        </div>
                                        <div class="{{ !empty($errors->first('role')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="role" class="col-sm-2 control-label"><b style="color: red;">* </b> User Role : </label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="role" id="role" required tabindex="5">
                                                    <option value="" {{ empty(old('role')) ? 'selected' : '' }}>Select User Role</option>
                                                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                                </select>
                                            </div>
                                            @if(!empty($errors->first('role')))
                                                <p style="color: red;" >{{$errors->first('role')}}</p>
                                            @endif
                                            {{-- <span class="fa fa-users form-control-feedback"></span> --}}
                                        </div>
                                        <div class="{{ !empty($errors->first('valid_till')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="valid_till" class="col-sm-2 control-label">User Validity : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="valid_till" class="form-control" placeholder="Keep this field empty for unlimited user validity." value="{{ !empty(old('valid_till')) ? old('valid_till') : '' }}" id="datepicker" tabindex="6">
                                            </div>
                                            @if(!empty($errors->first('valid_till')))
                                                <p style="color: red;" >{{$errors->first('valid_till')}}</p>
                                            @endif
                                        </div>
                                        <div class="{{ !empty($errors->first('password')) ? 'form-group has-error' : 'form-group' }}">
                                            <label for="password" class="col-sm-2 control-label"><b style="color: red;">* </b> Password : </label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password" class="form-control" placeholder="Password" required tabindex="7">
                                            </div>
                                            @if(!empty($errors->first('password')))
                                                <p style="color: red;" >{{$errors->first('password')}}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-sm-2 control-label"><b style="color: red;">* </b> Confirm Password : </label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required tabindex="8">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-xs-3"></div>
                                    <div class="col-xs-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="10">Clear</button>
                                    </div>
                                    <div class="col-xs-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="9">Submit</button>
                                    </div>
                                </div><br>
                            </div>
                        </form >
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