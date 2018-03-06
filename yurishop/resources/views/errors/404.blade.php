@extends('layouts.master')
 
@section('content')
  
        <div class="container">
            <div>
                <h1 id="homeHeading">404</h1>
                <h2>Nội dung không được tìm thấy!</h2>
                <hr>
                @include('notifications.status_message')
                @include('notifications.errors_message')
                <p>Xin lỗi, nội dung bạn tìm kiếm không có. Bạn có thể quay về
                <a href="{{ url('/') }}" >Trang chủ</a> hoặc 
                <a href="{{ url('/login') }}" >đăng nhập</a> hoặc 
                <a href="{{ url('/register') }}">đăng ký một tài khoản</a> </p>
            </div>
        </div>

@endsection