@extends('layouts.app')
@section('title', 'Truck Type And Royalty Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Truck Type And Royalty
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Truck Type And Royalty Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="float: left;">Truck Type Registration</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('vehicle-type-register-action')}}" method="post" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label"><b style="color: red;">* </b> Generic Name : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Generic name of the vehicle class" value="{{ old('name') }}" tabindex="1">
                                            @if(!empty($errors->first('name')))
                                                <p style="color: red;" >{{$errors->first('name')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-sm-2 control-label">Description : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                            @if(!empty(old('description')))
                                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Vehicle Type Description" style="resize: none;" tabindex="2">{{ old('description') }}</textarea>
                                            @else
                                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Vehicle Type Description" style="resize: none;" tabindex="2"></textarea>
                                            @endif
                                            @if(!empty($errors->first('description')))
                                                <p style="color: red;" >{{$errors->first('description')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="generic_quantity" class="col-sm-2 control-label"><b style="color: red;">* </b> Generic Quantity : </label>
                                        <div class="col-sm-10 {{ !empty($errors->first('generic_quantity')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control number_only" name="generic_quantity" id="generic_quantity" placeholder="Generic quantity" value="{{ old('generic_quantity') }}" tabindex="3">
                                        @if(!empty($errors->first('generic_quantity')))
                                            <p style="color: red;" >{{$errors->first('generic_quantity')}}</p>
                                        @endif
                                        </div>
                                    </div>
                                    @if(!empty($products))
                                        <div class="box-header with-border">
                                            <h3 class="box-title" style="float: left;">Royalty Details</h3>
                                        </div><br>
                                        @foreach($products as $product)
                                            <div class="form-group">
                                                <label for="royalty{{ $product->id }}" class="col-sm-2 control-label"><b style="color: red;">* </b> {{ $product->name}}: </label>
                                                <div class="col-sm-10 {{ !empty($errors->first('royalty.'.$product->id)) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control decimal_number_only" name="royalty[{{ $product->id }}]" id="royalty{{ $product->id }}" placeholder="Royalty amount for {{ $product->name }}" value="{{ old('royalty.'.$product->id) }}" tabindex="4">
                                                @if(!empty($errors->first('royalty.'.$product->id)))
                                                    <p style="color: red;" >{{$errors->first('royalty.'.$product->id)}}</p>
                                                @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="6">Clear</button>
                                </div>
                                {{-- <div class="col-sm-1"></div> --}}
                                <div class="col-xs-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" tabindex="5">Submit</button>
                                </div>
                                <!-- /.col -->
                            </div><br>
                        </div>
                    </form>
                </div>
                <!-- /.box primary -->
            </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection