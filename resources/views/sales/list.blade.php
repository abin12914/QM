@extends('layouts.app')
@section('title', 'Sale List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sale<small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        @if(!empty($errors->first('description')))
            <div class="alert alert-danger" id="alert-message">
                <h4>
                  {{ $errors->first('sale_id') }}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row  no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('sales-list-search') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-4     {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
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
                                        <div class="col-sm-4 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">Start Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="from_date" placeholder="Date" value="{{ !empty($fromDate) ? $fromDate : old('from_date') }}" tabindex="1">
                                            @if(!empty($errors->first('from_date')))
                                                <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                                            <label for="to_date" class="control-label">End Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="to_date" id="to_date" placeholder="Date" value="{{ !empty($toDate) ? $toDate : old('to_date') }}" tabindex="1">
                                            @if(!empty($errors->first('to_date')))
                                                <p style="color: red;" >{{$errors->first('to_date')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-4     {{ !empty($errors->first('vehicle_id')) ? 'has-error' : '' }}">
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
                                        <div class="col-sm-4 {{ !empty($errors->first('product_id')) ? 'has-error' : '' }}">
                                            <label for="product_id" class="control-label">Product : </label>
                                            <select class="form-control" name="product_id" id="product_id" tabindex="3" style="width: 100%">
                                                @if(!empty($products) && (count($products) > 0))
                                                    <option value="">Select product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ ((old('product_id') == $product->id ) || $productId == $product->id) ? 'selected' : '' }}>{{ $product->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('product')))
                                                <p style="color: red;" >{{$errors->first('product')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 {{ !empty($errors->first('vehicle_type_id')) ? 'has-error' : '' }}">
                                            <label for="vehicle_type_id" class="control-label">Truck Type : </label>
                                            <select class="form-control" name="vehicle_type_id" id="vehicle_type_id" tabindex="3" style="width: 100%">
                                                @if(!empty($vehicleTypes) && (count($vehicleTypes) > 0))
                                                    <option value="">Select vehicle type</option>
                                                    @foreach($vehicleTypes as $vehicleType)
                                                        <option value="{{ $vehicleType->id }}" {{ ((old('vehicle_type_id') == $vehicleType->id ) || $vehicleTypeId == $vehicleType->id) ? 'selected' : '' }}>{{ $vehicleType->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('vehicle_type_id')))
                                                <p style="color: red;" >{{$errors->first('vehicle_type_id')}}</p>
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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 2%;">#</th>
                                            <th style="width: 15%;">Date / Ref. No.</th>
                                            <th style="width: 13%;">Truck Number</th>
                                            <th style="width: 10%;">Product</th>
                                            <th style="width: 20%;">Purchaser</th>
                                            <th style="width: 10%;">Quantity</th>
                                            <th style="width: 5%;">No Of Load</th>
                                            <th style="width: 10%;">Bill Amount</th>
                                            <th style="width: 15%;" class="no-print">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($sales))
                                            @foreach($sales as $index=>$sale)
                                                <tr>
                                                    <td>{{ $index + $sales->firstItem() }}</td>
                                                    <td>
                                                        {{ Carbon\Carbon::parse($sale->date_time)->format('d-m-Y') }}<i class="no-print"> / </i>
                                                        <b class="no-print bg-info text-red">{{ $sale->transaction->id }}</b>
                                                    </td>
                                                    <td>{{ $sale->vehicle->reg_number }} - {{ $sale->vehicle->vehicleType->name }}</td>
                                                    <td>{{ $sale->product->name }}</td>
                                                    <td>{{ $sale->transaction->debitAccount->account_name }}</td>
                                                    @if($sale->measure_type == 1)
                                                        <td>{{ $sale->quantity }} - Cubic feet</td>
                                                        <td>1</td>
                                                        <td title="{{ $sale->quantity }} * {{ $sale->rate }}">{{ $sale->total_amount }}</td>
                                                        <td class="no-print">
                                                            @if($sale->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                <form action="{{route('sale-delete-action')}}" id="delete_{{ $sale->id }}" method="post" style="float: left;">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                                                                    <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($sale->date_time)->format('d-m-Y') }}">
                                                                    <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $sale->id }}" type="button">
                                                                        <i class="fa fa-trash"> Delete</i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                    <i class="fa fa-exclamation-circle"> No Access</i>
                                                                </button>
                                                            @endif
                                                            &nbsp;
                                                            <button class="btn btn-default">
                                                                <a href="{{ route('sales-bill-print', ['id' => $sale->id]) }}" target="_blank"><i class="fa fa-print"></i> Print Bill</a>
                                                            </button>
                                                        </td>
                                                    @elseif($sale->measure_type == 2)
                                                        @if($sale->quantity <= 0)
                                                            <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                            <td>1</td>
                                                            <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                            <td class="no-print" title="Quantity updation pending" tooltip>
                                                                @if($sale->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                    <form action="{{route('sale-delete-action')}}" id="delete_{{ $sale->id }}" method="post" style="float: left;">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                                                                        <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($sale->date_time)->format('d-m-Y') }}">
                                                                        <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $sale->id }}" type="button">
                                                                            <i class="fa fa-trash"> Delete</i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                        <i class="fa fa-exclamation-circle"> No Access</i>
                                                                    </button>
                                                                @endif
                                                                &nbsp;
                                                                <button class="btn btn-default">
                                                                    <i class="fa fa-hourglass-half"></i>
                                                                </button>
                                                            </td>
                                                        @else
                                                            <td>{{ $sale->quantity }} - Ton</td>
                                                            <td>1</td>
                                                            <td title="{{ $sale->quantity }} * {{ $sale->rate }}">{{ $sale->total_amount }}</td>
                                                            <td class="no-print">
                                                                @if($sale->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                    <form action="{{route('sale-delete-action')}}" id="delete_{{ $sale->id }}" method="post" style="float: left;">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                                                                        <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($sale->date_time)->format('d-m-Y') }}">
                                                                        <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $sale->id }}" type="button">
                                                                            <i class="fa fa-trash"> Delete</i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                        <i class="fa fa-exclamation-circle"> No Access</i>
                                                                    </button>
                                                                @endif
                                                                &nbsp;
                                                                <button class="btn btn-default">
                                                                    <a href="{{ route('sales-bill-print', ['id' => $sale->id]) }}" target="_blank"><i class="fa fa-print"></i> Print Bill</a>
                                                                </button>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td>{{ ($sale->quantity * $sale->vehicle->volume) }} - Cubic feet</td>
                                                        <td>{{ $sale->quantity }}</td>
                                                        <td title="{{ $sale->quantity }} * {{ $sale->rate }} - {{ $sale->discount }}">{{ $sale->total_amount }}</td>
                                                        <td class="no-print" title="Multiple sales" tooltip>
                                                            @if($sale->transaction->created_user_id == Auth::id() || Auth::user()->role == 'admin')
                                                                <form action="{{route('sale-delete-action')}}" id="sale_delete_{{ $sale->id }}" method="post" style="float: left;">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                                                                    <input type="hidden" name="date" value="{{ Carbon\Carbon::parse($sale->date_time)->format('d-m-Y') }}">
                                                                    <button type="button" class="btn btn-danger delete_button" data-delete-id="{{ $sale->id }}" type="button">
                                                                        <i class="fa fa-trash"> Delete</i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <button type="button" class="btn btn-default button-disabled" style="float: left;">
                                                                    <i class="fa fa-exclamation-circle"> No Access</i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                        @if(!empty($sales) && (Request::get('page') == $sales->lastPage() || $sales->lastPage() == 1))
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Total Amount</b></td>
                                                <td></td>
                                                <td>aprox. <b>{{ $totalQuantity }} Cft</b></td>
                                                <td><b>{{ $totalLoad }}</b></td>
                                                <td><b>{{ $totalAmount }}</b></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row  no-print">
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
                    <!-- /.box-body -->
                </div>
                <!-- /.boxy -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
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
    <script src="/js/list/sales.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection