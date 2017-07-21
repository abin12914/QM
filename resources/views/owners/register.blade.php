@extends('layouts.app')
@section('title', 'Owner Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Owner
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Owner</a></li>
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
                            <h3 class="box-title" style="float: left;">Owner Registration</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('owner-register-action')}}" method="post" class="form-horizontal" multipart-form-data>
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
                                            <label for="email" class="col-sm-2 control-label">E-mail : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('email')) ? 'has-error' : '' }}">
                                                <input type="text" name="email" class="form-control" id="email" placeholder="E-mail" value="{{ old('email') }}" tabindex="2">
                                                @if(!empty($errors->first('email')))
                                                    <p style="color: red;" >{{$errors->first('email')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone" class="col-sm-2 control-label"><b style="color: red;">* </b> Phone : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('phone')) ? 'has-error' : '' }}">
                                                <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="3">
                                                @if(!empty($errors->first('phone')))
                                                    <p style="color: red;" >{{$errors->first('phone')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-sm-2 control-label">Address : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('address')) ? 'has-error' : '' }}">
                                                @if(!empty(old('address')))
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="4">{{ old('address') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="4"></textarea>
                                                @endif
                                                @if(!empty($errors->first('address')))
                                                    <p style="color: red;" >{{$errors->first('address')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="image_file" class="col-sm-2 control-label">Image : </label>
                                            <div class="col-sm-5 {{ !empty($errors->first('image_file')) ? 'has-error' : '' }}">
                                                <input type="file" name="image_file" class="form-control" id="image_file" accept="image/*" tabindex="5">
                                                @if(!empty($errors->first('image_file')))
                                                    <p style="color: red;" >{{$errors->first('image_file')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-sm-5 {{ !empty($errors->first('image_file')) ? 'has-error' : '' }}">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" name="royalty_owner" id="royalty_owner" value="1" {{ old('royalty_owner') == '1' ? 'checked' : ''}}>
                                                        </span>
                                                        <label for="royalty_owner" class="form-control" tabindex="20">Make Royalty Owner</label>
                                                    </div>
                                                @if(!empty($errors->first('royalty_owner')))
                                                    <p style="color: red;" >{{$errors->first('royalty_owner')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="user_name" class="col-sm-2 control-label"><b style="color: red;">* </b> User Name : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                                <input type="text" name="user_name" class="form-control" placeholder="User Name" value="{{ old('user_name') }}" tabindex="6" >
                                                @if(!empty($errors->first('user_name')))
                                                    <p style="color: red;" >{{$errors->first('user_name')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="valid_till" class="col-sm-2 control-label">User Validity : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('valid_till')) ? 'has-error' : '' }}">
                                                <input type="text" name="valid_till" class="form-control" placeholder="Keep this field empty for unlimited user validity." value="{{ !empty(old('valid_till')) ? old('valid_till') : '' }}" id="datepicker" tabindex="7">
                                                @if(!empty($errors->first('valid_till')))
                                                    <p style="color: red;" >{{$errors->first('valid_till')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="col-sm-2 control-label"><b style="color: red;">* </b> Password : </label>
                                            <div class="col-sm-10 {{ count($errors) > 0 ? 'has-error' : '' }}">
                                                <input type="password" name="password" class="form-control" placeholder="Password"  tabindex="8">
                                                @if(!empty($errors->first('password')))
                                                    <p style="color: red;" >{{$errors->first('password')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-sm-2 control-label"><b style="color: red;">* </b> Confirm Password : </label>
                                            <div class="col-sm-10 {{ count($errors) > 0 ? 'has-error' : '' }}">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password"  tabindex="9">
                                                @if(!empty($errors->first('password')))
                                                    <p style="color: red;" >{{$errors->first('password')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="account_name" class="col-sm-2 control-label"><b style="color: red;">* </b> Account Name : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('account_name')) ? 'has-error' : '' }}">
                                                <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name') }}"  tabindex="10">
                                                @if(!empty($errors->first('account_name')))
                                                    <p style="color: red;" >{{$errors->first('account_name')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('financial_status')) ? 'has-error' : '' }}">
                                                <select class="form-control" name="financial_status" id="financial_status" tabindex="11">
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
                                                <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="{{ old('opening_balance') }}" tabindex="12">
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
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="14">Clear</button>
                                    </div>
                                    {{-- <div class="col-sm-1"></div> --}}
                                    <div class="col-xs-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="13">Submit</button>
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
    <script src="/js/ownerRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection