@extends('layouts.app')
@section('title', 'Jackhammer Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Jackhammer
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Jackhammer Registration</li>
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
                        <h3 class="box-title" style="float: left;">Jackhammer Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('jackhammer-register-action')}}" method="post" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Machine Name : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" tabindex="1">
                                        @if(!empty($errors->first('name')))
                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-2 control-label">Description : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product Description" style="resize: none;" tabindex="2">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Product Description" style="resize: none;" tabindex="2"></textarea>
                                        @endif
                                        @if(!empty($errors->first('description')))
                                            <p style="color: red;" >{{$errors->first('description')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contractor_account_id" class="col-sm-2 control-label"><b style="color: red;">* </b> Contractor : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('contractor_account_id')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="contractor_account_id" id="contractor_account_id" tabindex="3">
                                            <option value="" {{ empty(old('contractor_account_id')) ? 'selected' : '' }}>Select contractor or provider account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ $account->id == old('contractor_account_id') ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                        @if(!empty($errors->first('contractor_account_id')))
                                            <p style="color: red;" >{{$errors->first('contractor_account_id')}}</p>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="rent_type" class="col-sm-2 control-label"><b style="color: red;">* </b> Rental Type : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rent_type')) ? 'has-error' : '' }}">
                                        <select class="form-control" name="rent_type" id="rent_type" tabindex="4">
                                            <option value="" selected="" {{ empty(old('rent_type')) ? 'selected' : '' }}>Select rental type</option>
                                            <option value="per_day" {{ old('rent_type') == 'per_day' ? 'selected' : '' }}>Rent per day</option>
                                            <option value="per_feet" {{ old('rent_type') == 'per_feet' ? 'selected' : '' }}>Rent per feet</option>
                                        </select>
                                        @if(!empty($errors->first('rent_type')))
                                            <p style="color: red;" >{{$errors->first('rent_type')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rate_daily" class="col-sm-2 control-label"><b style="color: red;">* </b> Rent/Day : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rate_daily')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="rate_daily" id="rate_daily" placeholder="Rate per month" value="{{ old('rate_daily') }}" tabindex="5">
                                        @if(!empty($errors->first('rate_daily')))
                                            <p style="color: red;" >{{$errors->first('rate_daily')}}</p>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <label for="rate_feet" class="col-sm-2 control-label"><b style="color: red;">* </b> Rate/Feet : </label>
                                    <div class="col-sm-10 {{ !empty($errors->first('rate_feet')) ? 'has-error' : '' }}">
                                        <input type="text" class="form-control decimal_number_only" name="rate_feet" id="rate_feet" placeholder="Rate per feet" value="{{ old('rate_feet') }}" tabindex="6">
                                        @if(!empty($errors->first('rate_feet')))
                                            <p style="color: red;" >{{$errors->first('rate_feet')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="7">Submit</button>
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
    <script src="/js/registration/jackhammerRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection