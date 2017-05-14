@extends('layouts.app')
@section('title', 'Account Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Account</a></li>
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
                        <h3 class="box-title" style="float: left;">Account Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('account-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}"  tabindex="1">
                                            @if(!empty($errors->first('name')))
                                                <p style="color: red;" >{{$errors->first('name')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-sm-2 control-label">Description : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                            @if(!empty(old('description')))
                                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2">{{ old('description') }}</textarea>
                                            @else
                                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2"></textarea>
                                            @endif
                                            @if(!empty($errors->first('description')))
                                                <p style="color: red;" >{{$errors->first('description')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="account_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Account Type : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('account_type')) ? 'has-error' : '' }}">
                                            <select class="form-control" name="account_type" id="account_type" tabindex="3">
                                                <option value="" {{ empty(old('financial_status')) ? 'selected' : '' }}>Select account type</option>
                                                @foreach($accountTypes as $accountType)
                                                    <option value="{{ $accountType->value }}" {{ (old('account_type') == $accountType->value) ? 'selected' : '' }}>{{ $accountType->name }}</option>
                                                @endforeach
                                            </select>
                                            @if(!empty($errors->first('account_type')))
                                                <p style="color: red;" >{{$errors->first('account_type')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="financial_status" class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('financial_status')) ? 'has-error' : '' }}">
                                            <select class="form-control" name="financial_status" id="financial_status"  tabindex="4">
                                                <option value="" {{ empty(old('financial_status')) ? 'selected' : '' }}>Select financial status</option>
                                                <option value="none" {{ (old('financial_status') == 'none') ? 'selected' : '' }}>None (No pending transactions)</option>
                                                <option value="credit" {{ (old('financial_status') == 'credit') ? 'selected' : '' }}>Debitor (Account holder owe company)</option>
                                                <option value="debit" {{ (old('financial_status') == 'debit') ? 'selected' : '' }}>Creditor (Company owe account holder)</option>
                                            </select>
                                            @if(!empty($errors->first('financial_status')))
                                                <p style="color: red;" >{{$errors->first('financial_status')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="opening_balance" class="col-sm-2 control-label"><b style="color: red;">* </b> Opening Balance : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('opening_balance')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ old('opening_balance') }}" ="" tabindex="5" onkeypress="return isDecimalNumberKey(event)">
                                            @if(!empty($errors->first('opening_balance')))
                                                <p style="color: red;" >{{$errors->first('opening_balance')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="7">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="6">Submit</button>
                                </div>
                                <!-- /.col -->
                            </div><br>
                        </div>
                    </form>
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
    <script type="text/javascript">
        var decimalFlag = 0;

        function isDecimalNumberKey(evt){
            // attaching 1 to the end for number like 1.0
            var fieldValue = (document.getElementById(event.target.id).value+'1');
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57))) {
                return false;
            }
            if(charCode == 46) {
                if(decimalFlag == 1 && fieldValue % 1 != 0) {
                    return false;
                }
                decimalFlag = 1;
            }
            return true;
        }
    </script>
@endsection