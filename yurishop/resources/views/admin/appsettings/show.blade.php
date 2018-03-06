@extends('layouts.admin') 
@section('title', 'Thiếp Lập Hệ Thống') 
@section('description', 'This is a blank description that
needs to be implemented') 
@section('pageheader', 'Thiếp Lập Hệ Thống') 
@section('pagedescription', 'Chi Tiết') 
@section('breadarea','Thiết Lập') @section('breaditem', 'Chi Tiết') 

@section('content') 
@include('notifications.status_message') 
@include('notifications.errors_message')

@if (session()->has('success_message'))
<div class="alert alert-success">
    {{ session()->get('success_message') }}
</div>
@endif @if (session()->has('error_message'))
<div class="alert alert-danger">
    {{ session()->get('error_message') }}
</div>
@endif @section('content')

<div class="row">
    <div class="col-md-4">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user-2">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li><a href="#">Cấp Độ <span class="pull-right badge bg-aqua">{{$appsetting->vip)}} vnđ</span></a></li>
                    <li><a href="#">Điều Kiện <span class="pull-right badge bg-aqua">{{number_format($appsetting->freight_to_vn, 2, ',', '.')}} vnđ</span></a></li>
                    <li><a href="#">Phí Dịch Vụ (% giá trị đơn) <span class="pull-right badge bg-aqua">{{number_format($appsetting->freight_to_vn, 2, ',', '.')}} vnđ</span></a></li>
                    <li><a href="#">Tiền Cọc (% giá trị đơn) <span class="pull-right badge bg-aqua">{{number_format($appsetting->freight_to_vn, 2, ',', '.')}} vnđ</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    </a>
        @ability('admin,manager', 'appsetting-edit')
		<a class="btn btn-primary" href="{{ route('admin.appsettings.edit', $appsetting->id) }}" style="margin-left:8px;">
            <i class="fa fa-pencil-square-o"></i> Cập Nhật
        </a> 
		@endability
    </div>
</div>
@endsection