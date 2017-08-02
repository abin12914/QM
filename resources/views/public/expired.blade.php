@extends('layouts.public')
@section('title', 'Under Construction')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <p class="login-box-msg"></p>
            <div class="box-tools pull-right">
                <a href="{{ route('login-view') }}">
                    <button>
                        <i class="text-red">Back to login</i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4"></div>    
        <div class="col-md-4">
            <div id="image_div">
                <img style="width: 100%;" src="/images/public/account_expired.png">
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="login-logo">
                <div>
                    <b style="color: firebrick;">
                        Your trial period has been expired!
                    </b>
                    <br>
                </div>
            </div>
            <div class="login-box-body" style="border: powderblue; border-style: solid; border-width: thin;">
                <p class="login-box-msg">
                    @if(!empty(Session::get('expired-user')))
                        Username : {!! Session::get('expired-user') !!}<br>
                    @endif
                    Please contact the development team for more details.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection