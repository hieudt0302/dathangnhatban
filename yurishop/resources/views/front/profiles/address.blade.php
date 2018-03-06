@extends('layouts.master') 
@section('content')
<div class="container">
	<br>
	<div class="row">
		<div class="col-md-3">
			<?php echo View::make('front.pages.profilesidebar') ?>
		</div>
		<div class="col-md-9">
			<div class="row">
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>Whoops!</strong> Có một vài vấn đề với dữ liệu của bạn.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif @if ($message = Session::get('success'))
				<div class="alert alert-success">
					<p>{{ $message }}</p>
				</div>
				@elseif ($message = Session::get('danger'))
				<div class="alert alert-danger">
					<p>{{ $message }}</p>
				</div>
				@endif
			</div>
			<div class="row">
				<table class="table table-bordered">
					<tr>
						<th>#</th>
						<th>Địa Chỉ</th>
						<th>Điện Thoại</th>
						<th width="280px">Action</th>
					</tr>
					@foreach ($address as $key => $add)
					<tr>
						<td>{{ ++$i }}</td>
						<td>{{ $add->address }}, {{ $add->city }}</td>
						<td>{{ $add->phone }}</td>
						<td>
							<a class="btn btn-info" href="{{ route('front.profiles.showaddress',$add->id) }}">Xem</a>
							<a class="btn btn-primary" href="{{ route('front.profiles.editaddress',$add->id) }}">Sửa</a> {!! Form::open(['method' =>
							'DELETE','route' => ['front.profiles.destroyaddress', $add->id],'style'=>'display:inline']) !!} {!! Form::submit('Delete',
							['class' => 'btn btn-danger']) !!} {!! Form::close() !!}
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			<div class="row">
				<div class="pull-right">
	            	<a class="btn btn-success" href="{{ route('front.profiles.createaddress') }}">Thêm Địa Chỉ</a>
	       		</div>
			</div>
		</div>
	</div>
	<br>
</div>
@endsection