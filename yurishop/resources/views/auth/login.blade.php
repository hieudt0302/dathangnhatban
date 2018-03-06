@extends('layouts.master') @section('title') @lang('auth.login') @endsection @section('description', 'Login to the admin
area') @section('content')

<style type="text/css">
    .alert{
		font-size: 20px;
	}
</style>
<br>
<div class="container">
	<div class="card-container text-center">
		<div class="title">@lang('auth.login')</div>
		<div class="subtitle">@lang('auth.login_label')</div>
		@include('notifications.status_message') 
		@include('notifications.errors_message')
	</div>
	<div class="card card-container">
		<!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
		<img id="profile-img" class="profile-img-card" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" />
		<p id="profile-name" class="profile-name-card"></p>
		<form class="form-signin" method="POST" action="{{ url('/login') }}" data-parsley-validate="">
			{{ csrf_field() }}
			<input type="text" id="username" name="username" class="form-control" placeholder="Tên tài khoản" value="{{ old('username') }}"
			    required autofocus data-parsley-required-message="Yêu cầu nhập tên tài khoản" data-parsley-trigger="change focusout" data-parsley-minlength="5"
			    data-parsley-maxlength="20"> @if ($errors->has('username'))
			<span class="help-block">
						<strong>{{ $errors->first('username') }}</strong>
					</span> @endif
			<input type="password" id="password" name="password" class="form-control" placeholder="Mật khẩu" required data-parsley-required-message="Yêu cầu nhập mật khẩu"
			    data-parsley-trigger="change focusout" data-parsley-minlength="5" data-parsley-maxlength="32"> @if ($errors->has('password'))
			<span class="help-block">
						<strong>{{ $errors->first('password') }}</strong>
					</span> @endif
			<div id="remember" class="checkbox">
				<label>
                        <input type="checkbox" value="remember-me" name="remember"> @lang('auth.rememberme')
                    </label>
			</div>
			<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">@lang('auth.signin')</button>
		</form>
		<!-- /form -->
		<a href="{{ url('/password/reset') }}" class="forgot-password">@lang('auth.forgotpassword')</a> hoặc <a href="{{ url('/username/reminder') }}"
		    class="forgot-password">@lang('auth.forgotusername')</a>
	</div>
	<!-- /card-container -->
	<div class="card-container text-center">
		<a href="{{ url('/register') }}" class="new-account">@lang('auth.createaccount')</a> hoặc <a href="{{ url('/activation/resend') }}"
		    class="new-account">@lang('auth.resendactivationcode')</a>
	</div>
</div>
<!-- /container -->
<br> @endsection 
@section('scripts')
<script src="{{url('/')}}/public/assets/js/plugins/parsley/parsley.js"></script>
@endsection