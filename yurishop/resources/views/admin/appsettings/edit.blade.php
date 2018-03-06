@extends('layouts.admin') 
@section('title', 'Thiếp Lập Hệ Thống') 
@section('description', 'This is a blank description that
needs to be implemented') 
@section('pageheader', 'Thiếp Lập Hệ Thống') 
@section('pagedescription', 'Cập Nhật') 
@section('breadarea','Thiết Lập') @section('breaditem', 'Chi Tiết') 

@section('content') 
@include('notifications.status_message') 
@include('notifications.errors_message')

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-users"></i>
                <h3 class="box-title">Cập Nhật Thiết Lập</h3>
                <a type="button" class="btn btn-primary pull-right" href="{{ route('admin.appsettings.show') }}">
                        <i class="fa fa-chevron-left"></i> Trở Lại
                </a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        {!! Form::model($appsetting, ['method' => 'PATCH','route' => ['admin.appsettings.update', $appsetting->id]]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <strong>Phí Vận Chuyển Về Đà Nẵng:</strong> {!! Form::text('freight_to_vn', null, array('placeholder' =>
                                    '','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn-lg btn-primary">Cập Nhật</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection