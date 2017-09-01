@extends('layouts.app')
@section('title', 'Excavator List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Excavator
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Excavator List</li>
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
        <div class="row no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('excavator-list') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-6 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label">Contractor Account : </label>
                                            <select class="form-control" name="account_id" id="account_id" tabindex="3" style="width: 100%">
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    <option value="">Select contractor account</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ ((old('account_id') == $account->id ) || (!empty($accountId) && $accountId == $account->id)) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('account_id')))
                                                <p style="color: red;" >{{$errors->first('account_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 {{ !empty($errors->first('excavator_id')) ? 'has-error' : '' }}">
                                            <label for="excavator_id" class="control-label">Excavator : </label>
                                            <select class="form-control" name="excavator_id" id="excavator_id" tabindex="3" style="width: 100%">
                                                @if(!empty($excavatorsCombobox) && (count($excavatorsCombobox) > 0))
                                                    <option value="">Select excavator</option>
                                                    @foreach($excavatorsCombobox as $excavator)
                                                        <option value="{{ $excavator->id }}" {{ ((old('excavator_id') == $excavator->id ) || (!empty($excavatorId) && $excavatorId == $excavator->id)) ? 'selected' : '' }}>{{ $excavator->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('excavator_id')))
                                                <p style="color: red;" >{{$errors->first('excavator_id')}}</p>
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
                                            @foreach($excavators as $index => $excavator)
                                                <tr>
                                                    <td>{{ $index + $excavators->firstItem() }}</td>
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
                                </table>
                            </div>
                        </div>
                        <div class="row no-print">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        @if(!empty($excavators))
                                            {{ $excavators->appends(Request::all())->links() }}
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
    <script src="/js/list/excavator.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection