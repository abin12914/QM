@extends('layouts.app')
@section('title', 'Excavators')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Excavators
            {{-- <small>List</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Excavators</a></li>
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
                        <h3 class="box-title">Excavators List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contractor</th>
                                            <th>Rent Type</th>
                                            <th>Monthly Rent</th>
                                            <th>Hourly Rent : Bucket</th>
                                            <th>Hourly Rent : Breaker</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($excavators))
                                            @foreach($excavators as $excavator)
                                                <tr>
                                                    <td>{{ $excavator->name }}</td>
                                                    <td>{{ $excavator->account->account_name }}</td>
                                                    <td>{{ $excavator->rent_type }}</td>
                                                    <td>{{ $excavator->rent_monthly }}</td>
                                                    <td>{{ $excavator->rent_hourly_bucket }}</td>
                                                    <td>{{ $excavator->rent_hourly_breaker }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th><th></th><th></th><th></th><th></th><th></th>
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
                                        @if(!empty($excavators))
                                            {{ $excavators->links() }}
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