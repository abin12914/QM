@extends('layouts.app')
@section('title', 'Vehicle Types And Royalty')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Vehicle Types And Royalty
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Vehicle Types And Royalty</a></li>
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
                        <h3 class="box-title">Royalty Chart</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            @if(!empty($vehicletypes))
                                                <th>#</th>
                                                <th>Generic Name</th>
                                                <th>Product</th>
                                                <th>Royalty Amount</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($vehicletypes))
                                            @foreach($vehicletypes as $index => $vehicletype)
                                                @foreach($vehicletype->products as $product)
                                                    <tr>
                                                        <td>{{ $index + $vehicletypes->firstItem() }}</td>
                                                        <td>{{ $vehicletype->name }} ({{ $vehicletype->generic_quantity }} feet unit)</td>
                                                        <td>{{ $product->name }}</td>
                                                        <td>{{ $product->pivot->amount }}</td>
                                                    </tr>
                                                @endforeach
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
                                        @if(!empty($vehicletypes))
                                            {{ $vehicletypes->links() }}
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