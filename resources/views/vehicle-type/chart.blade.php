@extends('layouts.app')
@section('title', 'Truck Types And Royalty List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Truck Type And Royalty<small>Chart</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Vehicle Types And Royalty Chart</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Royalty Chart</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('vehicle-type-chart') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('vehicle_type_id')) ? 'has-error' : '' }}">
                                            <label for="vehicle_type_id" class="control-label">Truck Type : </label>
                                            <select class="form-control" name="vehicle_type_id" id="vehicle_type_id" tabindex="3" style="width: 100%">
                                                @if(!empty($vehicleTypesCombobox) && (count($vehicleTypesCombobox) > 0))
                                                    <option value="">Select truck type</option>
                                                    @foreach($vehicleTypesCombobox as $vehicleType)
                                                        <option value="{{ $vehicleType->id }}" {{ ((old('vehicle_type_id') == $vehicleType->id ) || (!empty($vehicleTypeId) && $vehicleTypeId == $vehicleType->id)) ? 'selected' : '' }}>{{ $vehicleType->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('vehicle_type_id')))
                                                <p style="color: red;" >{{$errors->first('vehicle_type_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 {{ !empty($errors->first('product_id')) ? 'has-error' : '' }}">
                                            <label for="product_id" class="control-label">Product : </label>
                                            <select class="form-control" name="product_id" id="product_id" tabindex="3" style="width: 100%">
                                                @if(!empty($products) && (count($products) > 0))
                                                    <option value="">Select product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ ((old('product_id') == $product->id ) || (!empty($productId) && $productId == $product->id)) ? 'selected' : '' }}>{{ $product->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('product_id')))
                                                <p style="color: red;" >{{$errors->first('product_id')}}</p>
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
                    </div><br>
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
                                            <th>Generic Name</th>
                                            <th>Generic Quantity</th>
                                            <th>Product</th>
                                            <th>Royalty Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($vehicleTypes))
                                            @foreach($vehicleTypes as $index => $vehicletype)
                                                @foreach($vehicletype->products as $k => $product)
                                                    @if(!empty($productId))
                                                        @if($product->id == $productId)
                                                            <tr>
                                                                <td>{{ $index + $vehicleTypes->firstItem() }}.{{ $k + 1 }}</td>
                                                                <td>{{ $vehicletype->name }}</td>
                                                                <td><b>{{ $vehicletype->generic_quantity }}</b><i> - cubic feet</i></td>
                                                                <td>{{ $product->name }}</td>
                                                                <td>{{ $product->pivot->amount }}</td>
                                                            </tr>
                                                        @endif
                                                    @else
                                                        <tr>
                                                            <td>{{ $index + $vehicleTypes->firstItem() }}.{{ $k + 1 }}</td>
                                                            <td>{{ $vehicletype->name }}</td>
                                                            <td><b>{{ $vehicletype->generic_quantity }}</b><i> - cubic feet</i></td>
                                                            <td>{{ $product->name }}</td>
                                                            <td>{{ $product->pivot->amount }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td></td><td></td><td></td><td></td><td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row no-print">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        @if(!empty($vehicleTypes))
                                            {{ $vehicleTypes->appends(Request::all())->links() }}
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
    <script src="/js/list/vehicleType.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection