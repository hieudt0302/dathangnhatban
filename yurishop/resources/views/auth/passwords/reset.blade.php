@extends('layouts.master') 
@section('title', 'Quên Mật Khẩu') 
@section('description', 'Quên Mật Khẩu') @section('content')
<br>
<div class="container">
	<div class="card-container text-center">
		<div class="title">Đổi Mật Khẩu</div>
		<div class="subtitle">Vui lòng thay thế bằng mật khẩu mới của bạn</div>
		@if (session('status'))
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> {{ session('status') }}
		</div>
		@endif @if ($errors->has('email'))
		<div class="alert alert-danger fade in">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> {{ $errors->first('email') }}
		</div>
		@endif
	</div>
	<div class="card card-container">
		<!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
		<img id="profile-img" class="profile-img-card" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" />
		<p id="profile-name" class="profile-name-card"></p>
		<form class="form-signin" method="POST" action="{{ url('/password/reset') }}" data-parsley-validate="">
			{{ csrf_field() }}
			<input type="hidden" name="token" value="{{ $token }}">
			<input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ $email or old('email') }}"
			    required autofocus data-parsley-required-message="Yêu cầu nhập địa chỉ email" data-parsley-trigger="change focusout" data-parsley-type="email">			@if ($errors->has('email'))
			<span class="help-block">
						<strong>{{ $errors->first('email') }}</strong>
					</span> @endif
			<input type="password" id="password" name="password" class="form-control" placeholder="Mật khẩu" required data-parsley-required-message="Yêu cầu nhập mật khẩu"
			    data-parsley-trigger="change focusout" data-parsley-minlength="6" data-parsley-maxlength="32"> @if ($errors->has('password'))
			<span class="help-block">
						<strong>{{ $errors->first('password') }}</strong>
					</span> @endif
			<input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu"
			    required data-parsley-required-message="Yêu cầu xác nhận mật khẩu" data-parsley-trigger="change focusout" data-parsley-minlength="6"
			    data-parsley-maxlength="32" data-parsley-equalto="#password" data-parsley-equalto-message="Mật khẩu xác nhận không phù hợp">			@if ($errors->has('password_confirmation'))
			<span class="help-block">
						<strong>{{ $errors->first('password_confirmation') }}</strong>
					</span> @endif
			<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Đổi Mật Khẩu</button>
		</form>
		<!-- /form -->
		<a href="{{ url('/username/reminder') }}" class="forgot-password">@lang('auth.forgotusername')</a>
	</div>
	<!-- /card-container -->
	<div class="card-container text-center">
		<a href="{{ url('/register') }}" class="new-account">@lang('auth.createaccount')</a> hoặc <a href="{{ url('/login') }}"
		    class="new-account">@lang('auth.login')</a>
	</div>

</div>
<!-- /container -->
<br> @endsection @section('scripts')
<script src="{{url('/')}}/public/assets/js/plugins/parsley/parsley.js"></script>
@endsection