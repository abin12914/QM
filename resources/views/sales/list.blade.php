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
                                            <th>#</th>
                                            <th>Date & Time</th>
                                            <th>Truck Number</th>
                                            <th>Product</th>
                                            <th>Purchaser</th>
                                            <th>Quantity</th>
                                            <th>No Of Load</th>
                                            <th>Bill Amount</th>
                                            <th class="no-print">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($sales))
                                            @foreach($sales as $index=>$sale)
                                                <tr>
                                                    <td>{{ $index + $sales->firstItem() }}</td>
                                                    <td>{{ $sale->date_time }}</td>
                                                    <td>{{ $sale->vehicle->reg_number }} - {{ $sale->vehicle->vehicleType->name }}</td>
                                                    <td>{{ $sale->product->name }}</td>
                                                    <td>{{ $sale->transaction->debitAccount->account_name }}</td>
                                                    @if($sale->measure_type == 1)
                                                        <td>{{ $sale->quantity }} - Cubic feet</td>
                                                        <td>1</td>
                                                        <td title="{{ $sale->quantity }} * {{ $sale->rate }}">{{ $sale->total_amount }}</td>
                                                        <td class="no-print">
                                                            <a href="{{ route('sales-bill-print', ['id' => $sale->id]) }}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print Bill</a>
                                                        </td>
                                                    @elseif($sale->measure_type == 2)
                                                        @if($sale->quantity <= 0)
                                                            <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                            <td>1</td>
                                                            <td title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                            <td class="no-print" title="Quantity updation pending" tooltip><i class="fa fa-hourglass-half"></i></td>
                                                        @else
                                                            <td>{{ $sale->quantity }} - Ton</td>
                                                            <td>1</td>
                                                            <td title="{{ $sale->quantity }} * {{ $sale->rate }}">{{ $sale->total_amount }}</td>
                                                            <td class="no-print">
                                                                <a href="{{ route('sales-bill-print', ['id' => $sale->id]) }}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print Bill</a>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td>{{ ($sale->quantity * $sale->vehicle->volume) }} - Cubic feet</td>
                                                        <td>{{ $sale->quantity }}</td>
                                                        <td title="{{ $sale->quantity }} * {{ $sale->rate }} - {{ $sale->discount }}">{{ $sale->total_amount }}</td>
                                                        <td class="no-print" title="Multiple sales" tooltip><i class="fa fa-thumbs-o-down"></i></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    @if(!empty($sales) && (Request::get('page') == $sales->lastPage() || $sales->lastPage() == 1))
                                        <tfoot>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Total Amount</b></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>{{ $totalLoad }}</b></td>
                                                <td><b>{{ $totalAmount }}</b></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    @endif
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
</div>
@endsection
@section('scripts')
    <script src="/js/list/sales.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection