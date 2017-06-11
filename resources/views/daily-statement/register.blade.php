@extends('layouts.app')
@section('title', 'Daily Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Daily Statement
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Daily statement</a></li>
            <li class="active">Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {{ Session::get('message') }}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <!-- nav-tabs-custom -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#labour_tab" data-toggle="tab">Labour</a></li>
                            <li><a href="#excavators_tab" data-toggle="tab">Excavators</a></li>
                            <li><a href="#jack_hammers_tab" data-toggle="tab">Jack-Hammers</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="labour_tab">
                                <!-- form start -->
                                <form action="{{ route('daily-statement-labour-action') }}" method="post" class="form-horizontal" multipart-form-data>
                                    <div class="box-body">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="date_credit" class="col-sm-1 control-label">Date : </label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date" placeholder="Date" value="{{ old('date') }}" tabindex="1">
                                                    </div>
                                                    <label for="date_credit" class="col-sm-3 control-label">No Of Labours : </label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" name="no_of_labours" id="no_of_labours" placeholder="No of labours" value="{{ old('no_of_labours') }}" readonly>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Account Name</th>
                                                            <th>Employee Name</th>
                                                            <th>Wage</th>
                                                            <th>#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>01</td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <select name="account_id" class="form-control account" id="account_id" tabindex="2" style="width: 100%">
                                                                            <option value="">Select labour account</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" name="labour_name" id="labour_name" value="Sebin John">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" name="wage" id="wage" value="1000" tabindex="3">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="clearfix"> </div><br>
                                        <div class="row">
                                            <div class="col-xs-3"></div>
                                            <div class="col-xs-3">
                                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="5">Clear</button>
                                            </div>
                                            <div class="col-xs-3">
                                                <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="4">Submit</button>
                                            </div>
                                            <!-- /.col -->
                                        </div><br>
                                    </div>
                                </form>
                                <!-- /.form end -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="excavators_tab">
                                <!-- form start -->
                                <form action="" id="" method="post" class="form-horizontal" multipart-form-data>
                                    <div class="box-body">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="transaction_type" value="cash">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <label for="product_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Product : </label>
                                                    <div class="col-sm-10 {{ !empty($errors->first('product_id_cash')) ? 'has-error' : '' }}">
                                                        <select name="product_id_cash" class="form-control product" id="product_cash" tabindex="3" style="width: 100%">
                                                            <option value="" {{ empty(old('product_id_cash')) ? 'selected' : '' }}>Select product</option>
                                                        </select>
                                                        @if(!empty($errors->first('product_id_cash')))
                                                            <p style="color: red;" >{{$errors->first('product_id_cash')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"> </div><br>
                                        <div class="row">
                                            <div class="col-xs-3"></div>
                                            <div class="col-xs-3">
                                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="9">Clear</button>
                                            </div>
                                            {{-- <div class="col-sm-1"></div> --}}
                                            <div class="col-xs-3">
                                                <button type="submit" id="submit_button" class="btn btn-primary btn-block btn-flat" tabindex="8">Submit</button>
                                            </div>
                                            <!-- /.col -->
                                        </div><br>
                                    </div>
                                </form>
                                <!-- /.form end -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="jack_hammers_tab">
                                <!-- form start -->
                                <form action="" id="" method="post" class="form-horizontal" multipart-form-data>
                                    <div class="box-body">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="transaction_type" value="cash">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <label for="product_cash" class="col-sm-2 control-label"><b style="color: red;">* </b> Product : </label>
                                                    <div class="col-sm-10 {{ !empty($errors->first('product_id_cash')) ? 'has-error' : '' }}">
                                                        <select name="product_id_cash" class="form-control product" id="product_cash" tabindex="3" style="width: 100%">
                                                            <option value="" {{ empty(old('product_id_cash')) ? 'selected' : '' }}>Select product</option>
                                                        </select>
                                                        @if(!empty($errors->first('product_id_cash')))
                                                            <p style="color: red;" >{{$errors->first('product_id_cash')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"> </div><br>
                                        <div class="row">
                                            <div class="col-xs-3"></div>
                                            <div class="col-xs-3">
                                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="9">Clear</button>
                                            </div>
                                            {{-- <div class="col-sm-1"></div> --}}
                                            <div class="col-xs-3">
                                                <button type="submit" id="submit_button" class="btn btn-primary btn-block btn-flat" tabindex="8">Submit</button>
                                            </div>
                                            <!-- /.col -->
                                        </div><br>
                                    </div>
                                </form>
                                <!-- /.form end -->
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/saleRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection