@extends('layouts.admin') @section('title', 'Thông Tin Tài Khoản') @section('description', 'This is a blank description that
needs to be implemented') @section('pageheader', 'Thông Tin Tài Khoản') @section('pagedescription', 'Chi Tiết') @section('breadarea','Tài
Khoản') @section('breaditem', 'Chi Tiết') @section('content') @include('notifications.status_message') @include('notifications.errors_message')
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
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
                <div class="widget-user-image">
                    <img class="img-circle" src="{{url('/')}}/public/assets/img/default-avatar.png" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{$user->last_name}} {{$user->first_name}}</h3>
                
                @if(!empty($user->roles)) 
				    @foreach($user->roles as $v)
					    <h5 class="widget-user-desc">{{ $v->display_name }} </h5>
					@endforeach 
				@endif
               
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li><a href="#">Tài Khoản<span class="pull-right badge bg-blue"> 
                        @if($user->activated)
                Đã kích hoạt
                @else
                Chưa kích hoạt
                @endif</span></a></li>
                    <li><a href="#">Điện Thoại <span class="pull-right badge bg-aqua">{{$user->phone}}</span></a></li>
                    <li><a href="#">Email<span class="pull-right badge bg-green">{{$user->email}}</span></a></li>
                    <!-- <li><a href="#">Đơn Hàng <span class="pull-right badge bg-red">842</span></a></li> -->
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    <a type="button" class="btn btn-default pull-left" href="{{ route('admin.users.index') }}">
                        <i class="fa fa-chevron-left"></i> Trở Lại
    </a>
        @ability('admin,manager', 'user-edit')
		<a class="btn btn-primary" href="{{ route('admin.users.edit',$user->id) }}" style="margin-left:8px;">
            <i class="fa fa-pencil-square-o"></i> Cập Nhật
        </a> 
		@endability
    </div>
</div>
@endsection