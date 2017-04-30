@extends('layouts.public')
@section('title', 'Register')
@section('content')
@if (Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
        <h4>
            {{ Session::get('message') }}
        </h4>
    </div>
@endif
<div class="login-box" style="background-color: #3c8dbc;">
    <div class="login-logo">
        <div>
            <b style="color: white;">
                Quary Manager
            </b>
            <br>
            <!-- <small>
                Login
            </small> -->
        </div>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body" style="border: powderblue; border-style: solid; border-width: thin;">
        <p class="login-box-msg">User Registration</p>
        <form action="{{route('register-action')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="{{ !empty($errors->first('name')) ? 'form-group has-feedback has-error' : 'form-group has-feedback' }}">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required>
                <span class="fa fa-pencil form-control-feedback"></span>
                @if(!empty($errors->first('name')))
                    <p style="color: red;" >{{$errors->first('name')}}</p>
                @endif
            </div>
            <div class="{{ !empty($errors->first('user_name')) ? 'form-group has-feedback has-error' : 'form-group has-feedback' }}">
                <input type="text" name="user_name" class="form-control" placeholder="User Name" value="{{ old('user_name') }}" required>
                <span class="fa fa-user form-control-feedback"></span>
                @if(!empty($errors->first('user_name')))
                    <p style="color: red;" >{{$errors->first('user_name')}}</p>
                @endif
            </div>
            <div class="{{ !empty($errors->first('email')) ? 'form-group has-feedback has-error' : 'form-group has-feedback' }}">
                <input type="email" name="email" class="form-control" placeholder="E-Mail" value="{{ !empty(old('email')) ? old('email') : '' }}">
                <span class="fa fa-envelope form-control-feedback"></span>
                @if(!empty($errors->first('email')))
                    <p style="color: red;" >{{$errors->first('email')}}</p>
                @endif
            </div>
            <div class="{{ !empty($errors->first('phone')) ? 'form-group has-feedback has-error' : 'form-group has-feedback' }}">
                <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone') }}" required>
                <span class="fa fa-phone form-control-feedback"></span>
                @if(!empty($errors->first('phone')))
                    <p style="color: red;" >{{$errors->first('phone')}}</p>
                @endif
            </div>
            <div class="{{ !empty($errors->first('role')) ? 'form-group has-feedback has-error' : 'form-group has-feedback' }}">
                <select class="form-control" name="role" required>
                    <option value="" {{ empty(old('role')) ? 'selected' : '' }}>Select User Role</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @if(!empty($errors->first('role')))
                    <p style="color: red;" >{{$errors->first('role')}}</p>
                @endif
                {{-- <span class="fa fa-users form-control-feedback"></span> --}}
            </div>
            <div class="{{ !empty($errors->first('valid_till')) ? 'form-group has-feedback has-error' : 'form-group has-feedback' }}">
                <input type="text" name="valid_till" class="form-control" placeholder="User Valid Till (Keep this field empty for unlimited user validity.)" value="{{ !empty(old('valid_till')) ? old('valid_till') : '' }}" id="datepicker">
                {{-- <input type="text" name="valid_till" class="form-control" placeholder="Valid Till" value="{{ old('valid_till') }}"> --}}
                <span class="fa fa-calendar form-control-feedback"></span>
                @if(!empty($errors->first('valid_till')))
                    <p style="color: red;" >{{$errors->first('valid_till')}}</p>
                @endif
            </div>
            <div class="{{ !empty($errors->first('password')) ? 'form-group has-feedback has-error' : 'form-group has-feedback' }}">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <span class="fa fa-lock form-control-feedback"></span>
                @if(!empty($errors->first('password')))
                    <p style="color: red;" >{{$errors->first('password')}}</p>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                <span class="fa fa-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
  <!-- /.login-box-body -->
</div>
@endsection