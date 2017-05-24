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