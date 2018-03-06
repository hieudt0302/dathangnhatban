

@extends('layouts.master')
@section('title','Đặt Hàng Trung Quốc - TaoBao, Tmall, 1688 - TaoBaoDaNang.Com')
@section('content')

<!-- <a href="test">Test</a> -->
<div class="home">
    <div class="order-float-bar-block" id="order-bar">
        <div class="tab-btn block-get-info" data-tab="tab-product-info">
            {!! Form::open(['url' => '/thong-tin-san-pham']) !!}
                <input type="text" class="input-link" id="product-link" name="product_url" autofocus="true"
                       placeholder="Nhập link sản phẩm">
                <button class="btn-get-info" type="submit">Lấy thông tin sản phẩm</button>
                <input type="hidden" id="link-hidden" value="">
            {!! Form::close() !!}
            @if (count($errors) > 0)
            <div class="input-error">
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif
        </div>
    </div>  
    <div class="guide-box">
        <span class="guide">Hướng dẫn sử dụng :</span>
        <ul>
            <li>
                Lấy link sản phẩm từ các trang : 
                <a href="https://taobao.com/" target="_blank">taobao.com</a>, 
                <a href="https://tmall.com/" target="_blank">tmall.com</a>, 
                <a href="https://1688.com/" target="_blank">1688.com</a>
            </li>    
            <li>
                Copy và dán link sản phẩm vào khung trên, click "Lấy thông tin sản phẩm"
            </li>
            <li>
                Chọn sản phẩm và đặt hàng
            </li>
        </ul>
    </div>
    <!--
    <div id="loading" style="display: none">
        <img src="{{url('/')}}/public/assets/img/loading.gif" alt="Loading..." />
    </div>
    -->
</div>  
@endsection
