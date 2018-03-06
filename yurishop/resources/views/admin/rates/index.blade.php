@extends('layouts.admin') @section('title', 'Tỷ Giá') @section('description', 'This is a blank description that needs to
be implemented') @section('pageheader', 'Tỷ Giá') @section('pagedescription', 'Danh sách') @section('breadarea', 'Tỷ Giá')
@section('breaditem', 'Cập Nhật') @section('content') @include('notifications.status_message') @include('notifications.errors_message')
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
				<h3 class="box-title">Cập Nhật Tỷ Giá Mới (₫)</h3>
			</div>
			<div class="box-body">
				{!! Form::open(array('route' => 'admin.rates.store','method'=>'POST', 'class'=>'form-inline')) !!}
				<div class="form-group">
					<label for="rate">Tỷ Giá Mới</label> {!! Form::text('rate', null, array('placeholder' => '0.00','class' =>'form-control'))
					!!}
				</div>
				<button type="submit" class="btn btn-primary">Cập Nhật</button> {!! Form::close() !!}
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<i class="fa fa-history"></i>
				<h3 class="box-title">Lịch Sử Thay Đổi</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered">
							<tr>
								<th>#</th>
								<th>Ngày Cập Nhật</th>
								<th>Tỷ Giá</th>
								<th>Người Cập Nhật</th>
							</tr>
							@foreach ($rates as $key => $rate)
							<tr>
								<td>{{ ++$i }}</td>
								<td>{{ $rate->updated_at }}</td>
								<td>{{ $rate->rate }}</td>
								<td>
									@if(!empty($rate->user)) 
									{{ $rate->user->last_name }} {{ $rate->user->first_name }} 
									@endif
								</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						{!! $rates->render() !!}
					</div>
				</div>
			</div>
		</div>
	</div>



	@endsection