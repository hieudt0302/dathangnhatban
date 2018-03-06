@extends('layouts.master') @section('title') @lang('auth.register') @endsection @section('description', 'Register to the
admin area') @section('content')
<br>
<div class="container">
	<div class="card-container text-center">
		<div class="title">@lang('auth.register')</div>
		<div class="subtitle">@lang('auth.register_label')</div>
		@include('notifications.status_message') @include('notifications.errors_message')
	</div>

	<div class="card card-container">
		<div class="client-feedback client-feedback-warning hidden">
			<h4>Xin lỗi, có một vài vấn đề!</h4>
			<p>Bạn cần hoàn thành các yêu cầu dưới đây</p>
		</div>

		<div class="client-feedback client-feedback-success hidden">
			<!-- <h4>Your A Champ!</h4>  -->
			<p>Tất cả thông tin của bạn phù hợp!</p>
		</div>
		<form class="form-signin" method="POST" action="{{ url('/register') }}" id="form-register" data-parsley-validate="">
			{{ csrf_field() }}
			<input type="text" id="username" name="username" class="form-control" placeholder="@lang('auth.placeholder_username')" value="{{ old('username') }}"
			  required autofocus data-parsley-required-message="Yêu cầu nhập tên tài khoản" data-parsley-trigger="change focusout" data-parsley-minlength="5" data-parsley-maxlength="20" onkeypress="return restrictCharacters(this, event, characters)"> 
			  @if ($errors->has('username'))
			<span class="help-block">
							<strong>{{ $errors->first('name') }}</strong>
						</span> @endif
			<input type="text" id="first_name" name="first_name" class="form-control" placeholder="@lang('auth.placeholder_firstname')"
			  value="{{ old('first_name') }}" required autofocus data-parsley-required-message="Yêu cầu nhập tên" data-parsley-trigger="change focusout"
			  data-parsley-minlength="2" data-parsley-maxlength="32"> @if ($errors->has('first_name'))
			<span class="help-block">
							<strong>{{ $errors->first('name') }}</strong>
						</span> @endif
			<input type="text" id="last_name" name="last_name" class="form-control" placeholder="@lang('auth.placeholder_lastname')"
			  value="{{ old('last_name') }}" required autofocus data-parsley-required-message="Yêu cầu nhập họ" data-parsley-trigger="change focusout"
			  data-parsley-minlength="2" data-parsley-maxlength="32"> @if ($errors->has('last_name'))
			<span class="help-block">
							<strong>{{ $errors->first('name') }}</strong>
						</span> @endif
			<input type="phone" id="phone" name="phone" class="form-control" placeholder="@lang('auth.placeholder_phone')" value="{{ old('phone') }}"
			  required autofocus data-parsley-required-message="Yêu cầu nhập số điện thoại" data-parsley-trigger="change focusout">			@if ($errors->has('phone'))
			<span class="help-block">
							<strong>{{ $errors->first('phone') }}</strong>
						</span> @endif
			<input type="email" id="email" name="email" class="form-control" placeholder="@lang('auth.placeholder_emailaddress')" value="{{ old('email') }}"
			  required autofocus data-parsley-required-message="Yêu cầu nhập địa chỉ Email" data-parsley-trigger="change focusout" data-parsley-type="email">			@if ($errors->has('email'))
			<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span> @endif

			<input type="password" id="password" name="password" class="form-control" placeholder="@lang('auth.placeholder_password')"
			  required data-parsley-required-message="Yêu cầu nhập mật khẩu" data-parsley-trigger="change focusout" data-parsley-minlength="5"
			  data-parsley-maxlength="32"> @if ($errors->has('password'))
			<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span> @endif
			<input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="@lang('auth.placeholder_confirmpassword')"
			  required data-parsley-required-message="Bạn cần xác nhận mật khẩu" data-parsley-trigger="change focusout" data-parsley-minlength="5"
			  data-parsley-maxlength="32" data-parsley-equalto="#password" data-parsley-equalto-message="Mật khẩu xác nhận không khớp">
			<div class="g-recaptcha" data-sitekey="{{ env('SETTINGS_GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>

			<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">@lang('auth.signup')</button>
		</form>
		<!-- /form -->
		<a href="{{ url('/password/reset') }}" class="forgot-password">@lang('auth.forgotpassword')</a> hoặc <a href="{{ url('/username/reminder') }}"
		  class="forgot-password">@lang('auth.forgotusername')</a>
	</div>
	<!-- /card-container -->
	<div class="card-container text-center">
		<a href="{{ url('/') }}" class="new-account">@lang('auth.backhome')</a> hoặc <a href="{{ url('/login') }}" class="new-account">@lang('auth.login')</a>
	</div>
</div>
<!-- /container -->
<br> @endsection @section('scripts')
<script src='https://www.google.com/recaptcha/api.js{{__('auth.recaptcha')}}'></script>
<script src="{{url('/')}}/public/assets/js/plugins/parsley/parsley.js"></script>
<script>
	$(function () {
		$('#form-register').parsley().on('field:validated', function () {
			var ok = $('.parsley-error').length === 0;
			$('.client-feedback-success').toggleClass('hidden', !ok);
			$('.client-feedback-warning').toggleClass('hidden', ok);
		});
	});
	var characters = /[a-z0-9_]/g;
    function restrictCharacters(myfield, e, restrictionType) {
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);

        // if they pressed esc... remove focus from field...
        if (code==27) { this.blur(); return false; }

        // ignore if they are press other keys
        // strange because code: 39 is the down key AND ' key...
        // and DEL also equals .
        //if (!e.ctrlKey && code!=9 && code!=8 && code!=36 && code!=37 && code!=38 && (code!=39 || (code==39 && character=="'")) && code!=40) {
		if (character.match(restrictionType)) {
			return true;
		} else {
			return false;
		}
                //}
    }
</script>
@endsection