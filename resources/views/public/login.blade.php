@extends('layouts.public')
@section('title', 'Login')
@section('content')
@if (Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
        <h4>
            {!! Session::get('message') !!}
        </h4>
    </div>
@endif
@if (Session::has('fixed-message'))
    <div class="alert {{ Session::get('fixed-alert-class', 'alert-info') }}" id="fixed-alert-message">
        <h4 style="margin-left: 20px;">
            {!! Session::get('fixed-message') !!}
        </h4>
    </div>
@endif
<div class="login-box" style="background-color: #3c8dbc;">
    <div class="login-logo">
        <div>
            <b style="color: white;">
                Quarry Manager
            </b>
            <br>
        </div>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body" style="border: powderblue; border-style: solid; border-width: thin;">
        <p class="login-box-msg">Log in to start your session</p>
        <form action="{{route('login-action')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group has-feedback">
                <input type="text" name="user_name" class="form-control" placeholder="User Name" value="{{ old('user_name') }}" required>
                <span class="fa fa-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <span class="fa fa-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button">Log In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <br>
        Forgot your password? Click<a href="{{ route('under-construction-view') }}"> here </a>to reset password.<br>
        <a class="pull-right" href="{{ route('licence-view') }}">License</a>
    </div>
  <!-- /.login-box-body -->
</div>
@endsection