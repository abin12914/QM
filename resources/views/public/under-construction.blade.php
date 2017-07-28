@extends('layouts.public')
@section('title', 'Under Construction')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <p class="login-box-msg"></p>
            <div class="box-tools pull-right">
                <a href="{{ !empty($currentUser)? route('user-dashboard') : route('login-view') }}">
                    <button>
                        <i class="text-red">Back to home</i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-2"></div>    
        <div class="col-md-8">
            <div id="image_div">
                <img style="width: 100%" src="/images/public/underConstruction.png">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <p class="login-box-msg">
                <i style="color: red;" class="fa fa-hand-o-up"></i>
                <b style="color: firebrick;">Sorry for the inconvenience. We will be back soon</b>
            </p>
        </div>
    </div>
</div>
@endsection