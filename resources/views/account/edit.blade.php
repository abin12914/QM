@extends('layouts.app')
@section('title', 'Account Updation')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account
            <small>Updation</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Account Updation</li>
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
        @if (count($errors) > 0)
            <div class="alert alert-danger" id="alert-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="float: left;">Account Updation</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    @if(!empty($account))
                        <!-- form start -->
                        <form action="{{route('account-updation-action')}}" method="post" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="account_id" value="{{ $account->id }}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="account_name" class="col-sm-2 control-label">Account Name : </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ $account->account_name }}"  tabindex="1" maxlength="200" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-sm-2 control-label">Description : </label>
                                            <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                                @if(!empty(old('description')))
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200">{{ old('description') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200">{{ $account->description }}</textarea>
                                                @endif
                                                @if(!empty($errors->first('description')))
                                                    <p style="color: red;" >{{$errors->first('description')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="account_type" class="col-sm-2 control-label">Account Type : </label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="account_type" id="account_type" disabled>
                                                    <option value="personal" selected>Personal account</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="personal_account_details">
                                            <br>
                                            <div class="box-header with-border">
                                                <h3 class="box-title" style="float: left;">Personal Details</h3>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Name : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                                    <input type="text" name="name" class="form-control" id="name" placeholder="Account holder name" value="{{ !empty(old('name')) ? old('name') : $account->accountDetail->name }}" tabindex="5">
                                                    @if(!empty($errors->first('name')))
                                                        <p style="color: red;" >{{$errors->first('name')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="col-sm-2 control-label"><b style="color: red;">* </b>  Phone : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('phone')) ? 'has-error' : '' }}">
                                                    <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ !empty(old('phone')) ? old('phone') : $account->accountDetail->phone}}" tabindex="6" maxlength="13">
                                                    @if(!empty($errors->first('phone')))
                                                        <p style="color: red;" >{{$errors->first('phone')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="address" class="col-sm-2 control-label">Address : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('address')) ? 'has-error' : '' }}">
                                                    @if(!empty(old('address')))
                                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="7" maxlength="200">{{ old('address') }}</textarea>
                                                    @else
                                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="7" maxlength="200">{{ $account->accountDetail->address }}</textarea>
                                                    @endif
                                                    @if(!empty($errors->first('address')))
                                                        <p style="color: red;" >{{$errors->first('address')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <label for="relation_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Primary Relation : </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('relation_type')) ? 'has-error' : '' }}">
                                                    <select class="form-control" name="relation_type" id="relation_type" tabindex="8" {{ (old('account_type') != 'personal')  && !empty(old('account_type')) ? 'disabled' : '' }}>
                                                        <option value="" {{ (empty(old('relation_type')) && empty($account->relation)) ? 'selected' : '' }}>Select primary relation type</option>
                                                        <option value="supplier" {{ ((old('relation_type') == 'supplier') || $account->relation == 'supplier') ? 'selected' : '' }}>Supplier</option>
                                                        <option value="customer" {{ ((old('relation_type') == 'customer') || $account->relation == 'customer') ? 'selected' : '' }}>Customer</option>
                                                        <option value="contractor" {{ ((old('relation_type') == 'contractor') || $account->relation == 'contractor') ? 'selected' : '' }}>Contractor</option>
                                                        <option value="general" {{ ((old('relation_type') == 'general') || $account->relation == 'general') ? 'selected' : '' }}>General/Other</option>
                                                    </select>
                                                    @if(!empty($errors->first('relation_type')))
                                                        <p style="color: red;" >{{$errors->first('relation_type')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="box-header with-border">
                                            <h3 class="box-title" style="float: left;">Financial Details</h3>
                                                <p>&nbsp&nbsp&nbsp</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="financial_status" class="col-sm-2 control-label">Financial Status : </label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="financial_status" id="financial_status"  tabindex="9" disabled>
                                                    <option value="" {{ (empty($account->financial_status)) ? 'selected' : '' }}>Select financial status</option>
                                                    <option value="none" {{ ($account->financial_status == 'none') ? 'selected' : '' }}>None (No pending transactions)</option>
                                                    <option value="credit" {{ ($account->financial_status == 'credit') ? 'selected' : '' }}>Debitor (Account holder owe company)</option>
                                                    <option value="debit" {{ ($account->financial_status == 'debit') ? 'selected' : '' }}>Creditor (Company owe account holder)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="opening_balance" class="col-sm-2 control-label">Opening Balance : </label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ $account->opening_balance }}" ="" tabindex="10" maxlength="8" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-xs-3"></div>
                                    <div class="col-xs-3">
                                        <a href="{{ route('account-list') }}"><button type="button" class="btn btn-default btn-block btn-flat" tabindex="12">Cancel & Exit</button></a>
                                    </div>
                                    <div class="col-xs-3">
                                        <button type="button" class="btn btn-primary btn-block btn-flat update-button" tabindex="11">Update</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                            </div>
                        </form>
                    @else
                        <div class="alert-danger" id="alert-message-fixed">
                            <h4>
                                <br>Something went wrong! Selected record not found. Try again after reloading the page!
                                <br>&nbsp;
                            </h4>
                        </div>
                    @endif
                </div>
                <!-- /.box primary -->
            </div>
            </div>
        </div>
        <!-- /.row (main row) -->
        <!-- /.row (main row) -->
        <div class="modal" id="update_confirmation_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Confirm Action</h4>
                    </div>
                    <div class="modal-body">
                        <div id="modal_warning">
                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <p style="color: red;">
                                        <b> Are you sure to update the record?</b>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="update_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel & Edit</button>
                        <button type="button" id="update_confirmation_modal_confirm" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/registration/accountRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection