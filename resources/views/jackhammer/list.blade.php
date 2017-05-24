@extends('layouts.app')
@section('title', 'Jackhammers')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Jackhammers
            {{-- <small>List</small> --}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Jackhammers</a></li>
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
                        <h3 class="box-title">Jackhammers List</h3>
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
                                            <th>Daily Rent</th>
                                            <th>Rent per Feet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($jackhammers))
                                            @foreach($jackhammers as $jackhammer)
                                                <tr>
                                                    <td>{{ $jackhammer->name }}</td>
                                                    <td>{{ $jackhammer->account->account_name }}</td>
                                                    <td>{{ $jackhammer->rent_type }}</td>
                                                    <td>{{ $jackhammer->rent_daily }}</td>
                                                    <td>{{ $jackhammer->rent_feet }}</td>
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
                                        @if(!empty($jackhammers))
                                            {{ $jackhammers->links() }}
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