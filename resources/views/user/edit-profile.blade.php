@extends('layouts.app')
@section('title', 'User Profile Edit')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            User Profile
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User Profile Edit</li>
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
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="float: left;">User Profile Edit</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <form action="{{route('profile-update-action')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ !empty($currentUser->name) ? $currentUser->name : "" }}" tabindex="1" >
                                                @if(!empty($errors->first('name')))
                                                    <p style="color: red;" >{{$errors->first('name')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="user_name" class="col-sm-2 control-label"><b style="color: red;">* </b> User Name : </label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" placeholder="User Name" value="{{ !empty($currentUser->user_name) ? $currentUser->user_name : "" }}" tabindex="2" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="email" class="col-sm-2 control-label">E-mail : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('email')) ? 'has-error' : '' }}">
                                                <input type="email" name="email" class="form-control" placeholder="E-Mail" value="{{ !empty($currentUser->email) ? $currentUser->email : "" }}" tabindex="3" readonly>
                                                @if(!empty($errors->first('email')))
                                                    <p style="color: red;" >{{$errors->first('email')}}</p>
                                                @endif
                                            </div>
                                        </div> --}}<br><br>
                                        <div class="form-group">
                                            <label for="old_password" class="col-sm-2 control-label"><b style="color: red;">* </b> Current Password : </label>
                                            <div class="col-sm-10 {{ count($errors) > 0 ? 'has-error' : '' }}">
                                                <input type="password" name="old_password" class="form-control" placeholder="Password"  tabindex="8">
                                                @if(!empty($errors->first('old_password')))
                                                    <p style="color: red;" >{{$errors->first('old_password')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="col-sm-2 control-label"><b style="color: red;">* </b> New Password : </label>
                                            <div class="col-sm-10 {{ count($errors) > 0 ? 'has-error' : '' }}">
                                                <input type="password" name="password" class="form-control" placeholder="Password"  tabindex="8">
                                                @if(!empty($errors->first('password')))
                                                    <p style="color: red;" >{{$errors->first('password')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-sm-2 control-label"><b style="color: red;">* </b> Confirm New Password : </label>
                                            <div class="col-sm-10 {{ count($errors) > 0 ? 'has-error' : '' }}">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" tabindex="9">
                                                @if(!empty($errors->first('password')))
                                                    <p style="color: red;" >{{ $errors->first('password') }}</p>
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
                                    <div class="col-xs-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="10">Update</button>
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
@section('scripts')
    <script src="/js/registration/userRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection