@extends('layouts.app')
@section('title', 'User List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            User <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User List</li>
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
                        <h3 class="box-title">QuarryManager Users</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>User Name</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Valid Till</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($users))
                                            @foreach($users as $user)
                                                <tr class="{{ $user->role == 'admin' ? 'bg-success' : ($user->role == 'user' ? 'bg-info' : 'bg-warning') }}">
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->user_name }}</td>
                                                    <td>{{ $user->phone }}</td>
                                                    <td>{{ $user->role }}</td>
                                                    @if(!empty($user->valid_till))
                                                        <td>{{ $user->valid_till }}</td>
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
                                        @if(!empty($users))
                                            {{ $users->appends(Request::all())->links() }}
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