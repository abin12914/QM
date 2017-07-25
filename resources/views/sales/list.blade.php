@extends('layouts.app')
@section('title', 'Sales')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sales
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Sale</a></li>
            <li class="active">List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {{ Session::get('message') }}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Sale List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('sales-list-search') }}" method="get" class="form-horizontal" multipart-form-data>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-4     {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label">Purchaser : </label>
                                            <select class="form-control" name="account_id" id="account_id" tabindex="3" style="width: 100%">
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    <option value="">Select employee account</option>
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
                                        <div class="col-sm-4 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
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
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                                            <th>#</th>
                                            <th>Truck Number</th>
                                            <th>Truck Type</th>
                                            <th>Date & Time</th>
                                            <th>Product</th>
                                            <th>Purchaser</th>
                                            {{-- <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Discount</th> --}}
                                            <th>Bill Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($sales))
                                            @foreach($sales as $index=>$sale)
                                                <tr>
                                                    <td>{{ $index + $sales->firstItem() }}</td>
                                                    <td>{{ $sale->vehicle->reg_number }}</td>
                                                    <td>{{ $sale->vehicle->vehicleType->name }}</td>
                                                    <td>{{ $sale->date_time }}</td>
                                                    <td>{{ $sale->product->name }}</td>
                                                    <td>{{ $sale->transaction->debitAccount->account_name }}</td>
                                                    @if($sale->measure_type == 1 && !empty($sale->quantity))
                                                        {{-- <td>{{ $sale->quantity }}</td>
                                                        <td>{{ $sale->rate }}</td>
                                                        <td>{{ $sale->discount }}</td> --}}
                                                        <td>{{ $sale->total_amount }}</td>
                                                    @else
                                                        {{-- <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                        <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                        <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td> --}}
                                                        <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
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
</div>
@endsection