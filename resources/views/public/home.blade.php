@extends('layouts.public')
@section('title', 'Home')
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
<div class="login-box">
    <div class="login-logo">
        <div>
            <b>
                Welcome To Quarry Manager
            </b>
        </div>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body" style="border: powderblue; border-style: solid; border-width: thin;">
        <p class="login-box-msg"><b><i>Log in to start your session</i></b></p>
        <a href="{{route('login-view')}}"><button type="submit" class="btn btn-primary btn-block btn-flat">Log In</button></a>
        <br>
        <p class="login-box-msg">Contact the developer team for Signup and details.</p>
        <div class="pull-right"><a href="{{ route('licence-view') }}">License</a></div>
    </div>
  <!-- /.login-box-body -->
</div>
@endsection