@extends('layouts.app')
@section('title', 'Vehicles')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Vehicles
            {{-- <small>List</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Vehicles</a></li>
            <li class="active">List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
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
                        <h3 class="box-title">Vehicles List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('vehicle-list') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-4 {{ !empty($errors->first('vehicle_type_id')) ? 'has-error' : '' }}">
                                            <label for="vehicle_type_id" class="control-label">Vehicle Type : </label>
                                            <select class="form-control" name="vehicle_type_id" id="vehicle_type_id" tabindex="3" style="width: 100%">
                                                @if(!empty($vehicleTypes) && (count($vehicleTypes) > 0))
                                                    <option value="">Select truck type</option>
                                                    @foreach($vehicleTypes as $vehicleType)
                                                        <option value="{{ $vehicleType->id }}" {{ ((old('vehicle_type_id') == $vehicleType->id ) || (!empty($vehicleTypeId) && $vehicleTypeId == $vehicleType->id)) ? 'selected' : '' }}>{{ $vehicleType->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('vehicle_type_id')))
                                                <p style="color: red;" >{{$errors->first('vehicle_type_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 {{ !empty($errors->first('body_type')) ? 'has-error' : '' }}">
                                            <label for="body_type" class="control-label">Body Type : </label>
                                            <select class="form-control" name="body_type" id="body_type" tabindex="3" style="width: 100%">
                                                <option value="" {{ (empty($bodyType) || (empty(old('body_type')) && $bodyType == 0)) ? 'selected' : '' }}>Select body type</option>
                                                <option value="level" {{ (!empty($bodyType) && ((old('body_type') == 'level' ) || $bodyType == 'level')) ? 'selected' : '' }}>Level</option>
                                                <option value="extra-1" {{ (!empty($bodyType) && (old('body_type') == 'extra-1' || $bodyType == 'extra-1')) ? 'selected' : '' }}>Extended body</option>
                                                <option value="extra-2" {{ (!empty($bodyType) && (old('body_type') == 'extra-2' || $bodyType == 'extra-2')) ? 'selected' : '' }}>Extra extended body</option>
                                            </select>
                                            @if(!empty($errors->first('body_type')))
                                                <p style="color: red;" >{{$errors->first('body_type')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 {{ !empty($errors->first('vehicle_id')) ? 'has-error' : '' }}">
                                            <label for="vehicle_id" class="control-label">Truck Number : </label>
                                            <select class="form-control" name="vehicle_id" id="vehicle_id" tabindex="3" style="width: 100%">
                                                @if(!empty($vehiclesCombobox) && (count($vehiclesCombobox) > 0))
                                                    <option value="">Select truck</option>
                                                    @foreach($vehiclesCombobox as $vehicle)
                                                        <option value="{{ $vehicle->id }}" {{ ((old('vehicle_id') == $vehicle->id ) || (!empty($vehicleId) && $vehicleId == $vehicle->id)) ? 'selected' : '' }}>{{ $vehicle->reg_number }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('vehicle_id')))
                                                <p style="color: red;" >{{$errors->first('vehicle_id')}}</p>
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
                                            <th>Registration Number</th>
                                            <th>Owner</th>
                                            <th>Vehicle Type</th>
                                            <th>Capacity</th>
                                            <th>Body</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($vehicles))
                                            @foreach($vehicles as $vehicle)
                                                <tr>
                                                    <td>{{ $vehicle->reg_number }}</td>
                                                    <td>{{ $vehicle->owner_name }}</td>
                                                    <td>{{ $vehicle->vehicleType->name }}</td>
                                                    <td>{{ $vehicle->volume }}</td>
                                                    <td>{{ $vehicle->body_type }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th><th></th><th></th><th></th><th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        @if(!empty($vehicles))
                                            {{ $vehicles->links() }}
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
    <script src="/js/list/vehicles.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection