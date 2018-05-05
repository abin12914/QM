@extends('layouts.app')
@section('title', 'Weighment Pending List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Weighment Pending
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Weighment Pending List</li>
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
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <div class="box-header">
                        <form action="{{ route('sales-weighment-pending-view') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label">Purchaser : </label>
                                            <select class="form-control" name="account_id" id="account_id" tabindex="3" style="width: 100%">
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    <option value="">Select purchaser account</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ ((old('account_id') == $account->id ) || $accountId == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('account_id')))
                                                <p style="color: red;" >{{$errors->first('account_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 {{ !empty($errors->first('vehicle_id')) ? 'has-error' : '' }}">
                                            <label for="vehicle_id" class="control-label">Truck Number : </label>
                                            <select class="form-control" name="vehicle_id" id="vehicle_id" tabindex="3" style="width: 100%">
                                                @if(!empty($vehicles) && (count($vehicles) > 0))
                                                    <option value="">Select truck</option>
                                                    @foreach($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}" {{ ((old('vehicle_id') == $vehicle->id ) || $vehicleId == $vehicle->id) ? 'selected' : '' }}>{{ $vehicle->reg_number }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('vehicle_id')))
                                                <p style="color: red;" >{{$errors->first('vehicle_id')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">Start Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('from_date') }}" tabindex="1">
                                            @if(!empty($errors->first('from_date')))
                                                <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                                            <label for="to_date" class="control-label">End Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="to_date" id="to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('to_date') }}" tabindex="1">
                                            @if(!empty($errors->first('to_date')))
                                                <p style="color: red;" >{{$errors->first('to_date')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="10">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="box">
                    <div class="box-body">
                        @if(!empty($sales))
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">#</th>
                                        <th style="width: 20%;">Date / Ref. No.</th>
                                        <th style="width: 20%;">Truck Number</th>
                                        <th style="width: 20%;">Purchaser</th>
                                        <th style="width: 15%;">Product</th>
                                        <th class=" no-print" style="width: 15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $index => $saleRecord)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ Carbon\Carbon::parse($saleRecord->date_time)->format('d-m-Y') }}<i class="no-print"> / </i>
                                                <b class="no-print bg-info text-red">{{ $saleRecord->transaction->id }}</b>
                                            </td>
                                            <td>{{ $saleRecord->vehicle->reg_number }}</td>
                                            <td>{{ $saleRecord->transaction->debitAccount->account_name }}</td>
                                            <td>{{ $saleRecord->product->name }}</td>
                                            <td class=" no-print">
                                                <form action="{{route('sales-weighment-register-view')}}" id="sale_weighment_registration_form_{{ $index }}" method="get"style="float: left;">
                                                    <input type="hidden" name="sale_id" value="{{ $saleRecord->id }}">
                                                    <button type="submit" class="btn btn-info submit-button" type="button">Add</button>
                                                </form>
                                                @if($saleRecord->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                    <form action="{{route('sale-delete-action')}}" id="delete_{{ $saleRecord->id }}" method="post">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="sale_id" value="{{ $saleRecord->id }}">
                                                        <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($saleRecord->date_time)->format('d-m-Y') }}">
                                                        &nbsp;
                                                        <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $saleRecord->id }}" type="button">
                                                            <i class="fa fa-trash"> Delete</i>
                                                        </button>
                                                    </form>
                                                @else
                                                    &nbsp;
                                                    <button type="button" class="btn btn-default button-disabled">
                                                        <i class="fa fa-exclamation-circle"> No Access</i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="col-sm-5"></div>
                            <div class="col-sm-4">
                                <label>No sales records available to show!!</label>
                            </div>
                        @endif
                        <div class="row no-print">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        @if(!empty($sales))
                                            {{ $sales->appends(Request::all())->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <div class="modal modal modal-danger" id="delete_confirmation_modal">
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
                                <p>
                                    <b> Are you sure to delete this record?</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="delete_confirmation_modal_cancel" class="btn btn-default pull-left" data-dismiss="modal">Cancel & Edit</button>
                    <button type="button" id="delete_confirmation_modal_confirm" class="btn btn-primary" data-delete-modal-id="0" data-dismiss="modal">Confirm</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endsection
@section('scripts')
    <script src="/js/list/weighment.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection