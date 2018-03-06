@extends('layouts.admin')
@section('title', 'Lộ trình đơn hàng') 
@section('description', '') 
@section('pageheader', 'Lộ trình đơn hàng') 
@section('pagedescription', '') 
@section('breadarea', 'Đơn hàng')
@section('breaditem', 'Lộ trình') 

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
@endif

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Thay đổi lộ trình đơn hàng của shop</h3>
			</div>
			<div class="box-body">
				{!! Form::open(array('route' => 'admin.orders.set-route','method'=>'POST', 'class'=>'form-inline')) !!}
					<div class="form-group">
						<label for="rate">Mã vận đơn</label> 
						<input type="text" name="landingcode" class="form-control" placeholder="Nhập mã vận đơn của shop"
							   style="width: 300px; ">
					</div>
					<button type="submit" class="btn btn-primary">Đổi lộ trình</button> 
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection