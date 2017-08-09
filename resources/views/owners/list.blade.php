@extends('layouts.app')
@section('title', 'Account List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Account</a></li>
            <li class="active">List</li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Account List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Account Name</th>
                                            <th>User Name</th>
                                            <th>User Validity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($owners))
                                            @foreach($owners as $owner)
                                                <tr class="{{ $owner->account->relation == 'royalty owner' ? 'bg-info' : '' }}">
                                                    <td>{{ $owner->account->accountDetail->name }}</td>
                                                    <td>{{ $owner->account->accountDetail->phone }}</td>
                                                    <td>{{ $owner->account->account_name }}</td>
                                                    <td>{{ $owner->user->name }}</td>
                                                    @if(!empty($owner->user->valid_till))
                                                        <td>{{ $owner->user->valid_till }}</td>
                                                    @else
                                                        <td>Unlimited</td>
                                                    @endif
                                                </tr>
                                            @endforeach
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
                                        @if(!empty($owners))
                                            {{ $owners->links() }}
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