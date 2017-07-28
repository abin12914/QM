@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            My
            <small>Profile</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> User</a></li>
            <li class="active">Profile</li>
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
                            <h3 class="box-title" style="float: left;">{{ $user->name }}'s Profile</h3>
                        </div>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <!-- Profile Image -->
                                <div class="box-body box-profile">
                                    <img class="profile-user-img img-responsive img-circle" src="{{ $user->image }}" alt="User profile picture">
                                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                                    <p class="text-muted text-center">{{ $user->role }}</p>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <ul class="list-group list-group-unbordered">
                                            <li class="list-group-item">
                                                <b class="">Name : </b>
                                                <a class="pull-right">{{ !empty($user->name) ? $user->name : 'Nil' }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b class="">Username : </b>
                                                <a class="pull-right">{{ !empty($user->user_name) ? $user->user_name : 'Nil' }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b class="">E-mail : </b>
                                                <a class="pull-right">{{ !empty($user->email) ? $user->email : '    Nil' }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b class="">Phone : </b>
                                                <a class="pull-right">{{ !empty($user->phone) ? $user->phone : 'Nil' }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b class="">Role : </b>
                                                <a class="pull-right">{{ !empty($user->role) ? $user->role : 'Nil' }}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b class="">Valid Till : </b>
                                                <a class="pull-right">{{ !empty($user->valid_till) ? $user->valid_till : 'Unlimited' }}</a>
                                            </li>
                                        </ul><br>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-3">
                                            <a href="{{ route('under-construction-view') }}" class="btn btn-primary btn-block"><b>View Activity Log</b></a>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-3">
                                            <a href="{{ route('under-construction-view') }}" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
                                        </div>
                                        <div class="clearfix"></div><br>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
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