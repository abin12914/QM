@extends('layouts.public')
@section('title', 'License')
@section('content')
<div class="alert alert-info no-print">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h4 style="margin-left: 20px;">
                    <div class="row"><div class="col-md-4"></div><div class="col-md-5"><u>FREE SOFTWARE IS ABOUT LIBERTY</u></div></div><br>
                    <div class="row"><div class="col-md-1"></div><div class="col-md-11">FREEDOM OF USE | FREEDOM TO STUDY | FREEDOM TO COPY | FREEDOM TO IMPROVE</div></div>
                </h4>
            </div>
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
<div class="open-source no-print">
    <div class="login-box" style="border: powderblue; border-style: solid; border-width: thin;">
        <div class="login-logo">
            <div>
                <b>
                    LICENSE
                </b>
            </div>
        </div>
        <div class="login-box-body">
            &nbsp &nbsp Permission is hereby granted, free of charge, to any person obtaining a copy
            of this software and associated documentation files (the "Software"), to deal
            in the Software without restriction, including without limitation the rights
            to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
            copies of the Software, and to permit persons to whom the Software is
            furnished to do so, subject to the following conditions:<br><br>

            &nbsp &nbsp This permission notice shall be included in all
            copies or substantial portions of the Software.<br><br>

            &nbsp &nbsp THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
            IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
            FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
            AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
            LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
            OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
            SOFTWARE.<br><br>
        </div>
      <!-- /.login-box-body -->
    </div>
</div>
@endsection