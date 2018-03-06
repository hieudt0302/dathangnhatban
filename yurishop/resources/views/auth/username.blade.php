@extends('layouts.master') 
@section('title', 'Nhắc Tên Tài Khoản') @section('description', 'Nhắc Tên Tài Khoản')
@section('content')
<br>
<div class="container">
	<div class="card-container text-center">
		<div class="title">Nhắc Tên Tài Khoản</div>
		<div class="subtitle">Vui lòng cung cấp địa chỉ email của bạn</div>
		@include('notifications.status_message') 
		@include('notifications.errors_message')
	</div>
	<div class="card card-container">
		<!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
		<img id="profile-img" class="profile-img-card" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" />
		<p id="profile-name" class="profile-name-card"></p>
		<form class="form-signin" method="POST" action="{{ url('/username/reminder') }}">
			{{ csrf_field() }}
			<input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required
			    autofocus data-parsley-required-message="Yêu cầu nhập địa chỉ Email" data-parsley-trigger="change focusout"
					  data-parsley-type="email"> 
					@if ($errors->has('email'))
					<span class="help-block">
						<strong>{{ $errors->first('email') }}</strong>
					</span> 
					@endif

			<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Yêu Cầu Nhắc Tên Tài Khoản</button>
		</form>
		<!-- /form -->
		<a href="{{ url('/password/reset') }}" class="forgot-password">@lang('auth.forgotpassword')</a>
	</div>
	<!-- /card-container -->
	<div class="card-container text-center">
		<a href="{{ url('/register') }}" class="new-account">@lang('auth.createaccount')</a> hoặc <a href="{{ url('/login') }}" class="new-account">@lang('auth.login')</a>
	</div>

</div>
<!-- /container -->
<br> 
@endsection
@section('scripts') 
<script src="{{url('/')}}/public/assets/js/plugins/parsley/parsley.js"></script>
@endsection