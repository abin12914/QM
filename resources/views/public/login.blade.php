@extends('layouts.public')
@section('title', 'Login')
@section('content')
<div class="login-box">
    <div class="login-logo">
        <div>
            <b style="color: dodgerblue;">
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
        <p class="login-box-msg">Log in to start your session</p>
        <form action="" method="post">
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="User Name">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password">
                <span class="fa fa-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Log In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <br>
        Forgot your password? Click<a href="#"> here </a>to reset password.<br>
    </div>
  <!-- /.login-box-body -->
</div>
@endsection