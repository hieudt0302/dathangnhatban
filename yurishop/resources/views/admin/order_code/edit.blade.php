@extends('layouts.admin') 
@section('title', 'Mã đơn hàng') 
@section('description', 'This is a blank description that needs
to be implemented') 
@section('pageheader', 'Mã đơn hàng') 
@section('pagedescription', 'Cập Nhật') 
@section('breadarea', 'Mã đơn hàng') 
@section('breaditem', 'Cập nhật') 

@section('content') 
@include('notifications.status_message') 
@include('notifications.errors_message')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-list-alt"></i>
                <h3 class="box-title">Cập Nhật Mã Đơn Hàng</h3>
                <a type="button" class="btn btn-primary pull-right" href="{{ route('admin.order_code.index') }}">
                    <i class="fa fa-chevron-left"></i> Trở Lại
                </a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        {!! Form::open(array('route' => ['admin.order_code.update', $order_code->id ],'method'=>'POST', 'class'=>'form-inline')) !!}
                            <div class="row" style="margin: 5px 10px">
                                <label class="col-xs-3 control-label" title="">Mã đơn hàng</label>
                                <div class="col-xs-5">
                                    <input class="form-control" name="order_code" type="text" value="{{ $order_code->order_code }}" 
                                            style="width: 100%">
                                </div>
                            </div>
                            <div class="row" style="margin: 5px 10px">
                                <label class="col-xs-3 control-label" title="">Tên đơn hàng</label>
                                <div class="col-xs-5">
                                    <input class="form-control" name="order_name" type="text" value="{{ $order_code->order_name }}"
                                             style="width: 100%">
                                </div>
                            </div>
                            <div class="row" style="margin: 10px 10px">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-5">
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button> 
                                </div>
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