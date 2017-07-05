@extends('layouts.app')
@section('title', 'Purchases')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Purchases
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Purchase</a></li>
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
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="{{ ((old('tab_flag') == 'employee') || ( empty(Session::get('controller_tab_flag')) && (empty(old('tab_flag'))) ) || (Session::get('controller_tab_flag') == 'employee')) ? 'active' : '' }}"><a href="#employee_tab" data-toggle="tab">Employee Attendance List</a></li>
                            <li class="{{ (old('tab_flag') == 'excavator' || (!empty(Session::get('controller_tab_flag')) && (Session::get('controller_tab_flag') == 'excavator'))) ? 'active' : '' }}"><a href="#excavators_tab" data-toggle="tab">Excavator Readings</a></li>
                            <li class="{{ (old('tab_flag') == 'jackhammer' || (!empty(Session::get('controller_tab_flag')) && (Session::get('controller_tab_flag') == 'jackhammer'))) ? 'active' : '' }}"><a href="#jack_hammers_tab" data-toggle="tab">Jack-Hammer Readings</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="{{ (old('tab_flag') == 'employee' || (empty(Session::get('controller_tab_flag')) || Session::get('controller_tab_flag') == 'employee')) ? 'active' : '' }} tab-pane" id="employee_tab">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date & Time</th>
                                                        <th>Product</th>
                                                        <th>Supplier</th>
                                                        <th>Bill Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($purchases))
                                                        @foreach($purchases as $index=>$purchase)
                                                            <tr>
                                                                <td>{{ $index + $purchases->firstItem() }}</td>
                                                                <td>{{ $purchase->date_time }}</td>
                                                                <td>{{ $purchase->purchasebleProduct->name }}</td>
                                                                <td>{{ $purchase->transaction->creditAccount->account_name }}</td>
                                                                <td>{{ $purchase->bill_amount }}</td>
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
                                                    @if(!empty($purchases))
                                                        {{ $purchases->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ (old('tab_flag') == 'excavators' || Session::get('controller_tab_flag') == 'excavators') ? 'active' : '' }} tab-pane" id="excavators_tab">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($purchases))
                                                        {{ $purchases->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="{{ (old('tab_flag') == 'jack_hammers' || Session::get('controller_tab_flag') == 'jack_hammers') ? 'active' : '' }} tab-pane" id="jack_hammers_tab">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>***</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="pull-right">
                                                    @if(!empty($purchases))
                                                        {{ $purchases->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
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