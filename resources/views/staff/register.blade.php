@extends('layouts.app')
@section('title', 'Staff Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Staff
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Staff</a></li>
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
                        <h3 class="box-title" style="float: left;">Staff Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('staff-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" tabindex="1">
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-sm-2 control-label"><b style="color: red;">* </b> Phone : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('phone')) ? 'has-error' : '' }}">
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="2" onkeypress="return isNumberKey(event)">
                                        @if(!empty($errors->first('phone')))
                                            <p style="color: red;" >{{$errors->first('phone')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-sm-2 control-label">Address : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('address')) ? 'has-error' : '' }}">
                                        @if(!empty(old('address')))
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3">{{ old('address') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3"></textarea>
                                        @endif
                                        @if(!empty($errors->first('address')))
                                            <p style="color: red;" >{{$errors->first('address')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image_file" class="col-sm-2 control-label">Image : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('image_file')) ? 'has-error' : '' }}">
                                        <input type="file" name="image_file" class="form-control" id="image_file" tabindex="4">
                                        @if(!empty($errors->first('image_file')))
                                            <p style="color: red;" >{{$errors->first('image_file')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="salary" class="col-sm-2 control-label"><b style="color: red;">* </b> Monthly Salary : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('salary')) ? 'has-error' : '' }}">
                                        <input type="text" name="salary" class="form-control" id="salary" placeholder="Monthly Salary" value="{{ old('salary') }}" tabindex="5" onkeypress="return isDecimalNumberKey(event)">
                                        @if(!empty($errors->first('salary')))
                                            <p style="color: red;" >{{$errors->first('salary')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('financial_status')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="financial_status" id="financial_status" tabindex="6">
                                            <option value="" {{ empty(old('financial_status')) ? 'selected' : '' }}>Select Status</option>
                                            <option value="none" {{ old('financial_status') == 'none' ? 'selected' : '' }}>None (No pending transactions)</option>
                                            <option value="debit" {{ old('financial_status') == 'debit' ? 'selected' : '' }}>Debitor (Account Holder Owe Company)</option>
                                            <option value="credit" {{ old('financial_status') == 'credit' ? 'selected' : '' }}>Creditor (Company Owe Account Holder)</option>
                                        </select>
                                        @if(!empty($errors->first('financial_status')))
                                            <p style="color: red;" >{{$errors->first('financial_status')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><b style="color: red;">* </b> Opening Balance: </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('opening_balance')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="{{ old('opening_balance') }}" tabindex="7" onkeypress="return isDecimalNumberKey(event)">
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
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="9">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="8">Submit</button>
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

        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

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