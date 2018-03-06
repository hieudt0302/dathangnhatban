

@extends('layouts.master')
@section('title','Đặt Hàng Trung Quốc - TaoBao, Tmall, 1688 - TaoBaoDaNang.Com')
@section('content')

<style type="text/css">
    .goog-te-banner-frame.skiptranslate {
        display: none !important;
    } 
    body {
        top: 0px !important; 
    }
    .goog-logo-link {
        display:none !important;
    } 
    .goog-te-gadget{
        color: transparent !important;
    }
    .goog-te-combo{
        color: #000;
    }
    .language{
        margin-top: 10px;
        float: right;
        padding-right: 25px;
    }
    .lang-label{
        float: left;
        padding-right: 10px;
        text-transform: capitalize;
    }
</style>
         
<div class="container">
    @if($state == true)
    <div class="row language">
        <span class="lang-label">Ngôn ngữ</span>
        <div id="google_translate_element"></div>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'zh-CN', includedLanguages: 'vi,zh-CN', layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT}, 'google_translate_element');
            }
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </div>
    @endif
    <div class="order-float-bar-block" id="order-bar">
        <div class="tab-btn block-get-info" data-tab="tab-product-info">
            {!! Form::open(['url' => '/thong-tin-san-pham']) !!}
                <input type="text" class="input-link" name="product_url" value="{{$product_url}}">
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
    <!--
    <div id="loading" style="display: none">
        <img src="{{url('/')}}/public/assets/img/loading.gif" alt="Loading..." />
    </div>
    -->
    @if($state == true)
    <div class="row" id="product-info">
        <input type="hidden" name="page" value="{{ $page }}">
        <input type="hidden" name="skuMap" value="{{ $skuMap }}">
        <input type="hidden" name="sizes" value="{{ $sizes }}">

        <?php
            $skuMap = json_decode($skuMap, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            $sizes = json_decode($sizes, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            
        ?>
        
        <input type="hidden" name="first" value="{{ $first }}">
        @if($page=='1688')
        <input type="hidden" name="default_price" value="{{ $default_price[0]->price }}">
        @endif
        <div class="col-md-4 product-img">
            <img class="img-responsive image product-img-main" src="{{$image}}" alt="Hình sản phẩm" />
        </div>
        <div class="col-md-8">
            <div class="product-name">
                {!! $page=='1688' ? $name : mb_convert_encoding($name,'UTF-8','GB2312') !!}
            </div>
            <div class="shop-box">
                <div class="col-md-2 detail-label">Cửa hàng</div>
                <div class="col-md-10 shop-name">
                    {!! $page=='1688' ? $shop_name : mb_convert_encoding($shop_name,'UTF-8','GB2312') !!}
                </div>
            </div>    
            <div class="price-box">
                <div class="col-md-2 detail-label">Giá sản phẩm</div>
                <div class="col-md-10">
                    @if(!empty($colors) OR !empty($sizes))
                        @if($page=='taobao' OR $page=='tmall')
                    <span class="price">{{$default_price}}</span>
                    <span class="currency">Tệ</span>
                        @elseif($page=='1688')
                            @foreach ($default_price as $p)
                        <span class="price-range">
                            {{$p->price}} <span class="currency">Tệ</span>
                        </span>
                            @endforeach
                        @endif
                    @else
                        @if($page=='taobao' OR $page=='tmall')
                    <input type="text" name="price" value="{{$default_price}}">
                    <span class="currency">Tệ</span>
                        @elseif($page=='1688')
                            @foreach ($default_price as $p)
                        <span class="price-range">
                            <input type="text" name="price" value="{{$p->price}}"> <span class="currency">Tệ</span>
                        </span>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
            @if($page=='1688')
            <div class="buy-quantity-box">
                 <div class="col-md-2 detail-label">Số lượng</div>
                 <div class="col-md-10">
                    @foreach ($default_price as $p)
                    <span class="quantity-range">
                        <span class="buy-quantity">> {{$p->startQuantity}}</span>
                    </span>
                    @endforeach
                 </div>
            </div>
            @endif

            @if(!empty($colors) AND !empty($sizes))
            <div class="color-box">
                <div class="col-md-2 detail-label">Màu sắc</div>
                <div class="col-md-10">
                    <ul class='color'>
                        @foreach ($colors as $key => $c)
                        <li class="single-color {!! $key==0 ? 'li-selected' : '' !!}" data-value="{{ $c['colorId'] }}" 
                           data-title="{!! $page=='1688' ? $c['colorName'] : mb_convert_encoding($c['colorName'],'UTF-8','GB2312') !!}">
                            <a href="javascript:;" title="{{ $c['colorName'] }}" >
                                @if($c['colorImg'] != '')
                                <img class="color-img" src="{{$c['colorImg']}}">
                                @else
                                <span class="color-text">
                                    {!! $page=='1688' ? $c['colorName'] : mb_convert_encoding($c['colorName'],'UTF-8','GB2312') !!}
                                </span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="size-box">
                <div class="col-md-2 detail-label">Kích cỡ</div>
                <div class="col-md-10">
                    @foreach ($sizes as $key => $s)
                    <div class="size attribute">
                        <span class="size-name attr-name">
                            {{ $s['sizeName'] }} 
                        </span>
                        <span class="size-price attr-price">
                            <input type="text" name="price" value="{{ $s['sizePrice'] }}">Tệ
                        </span>
                        <span class="size-quantity attr-quantity">
                            Còn <span class="quantity" data-key="attr_{{ $key }}">{{ $s['sizeQuantity'] }}</span>
                        </span>
                        <div class="amount">
                            <button type="button" class="btn btn-default btn-number" data-type="minus" 
                                    data-field="attr_{{ $key }}" disabled>
                                <span class="glyphicon glyphicon-minus"></span>
                            </button>
                            <input type="text" class="form-control input-number" name="attr_{{ $key }}" value="0" min="0">
                            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="attr_{{ $key }}">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>
                        <div class="note">
                            <input type="text" name="note" placeholder="Ghi chú">
                        </div>
                    </div>
                    
                    @endforeach
                    <div class="price-note">
                        Chú ý : Giá sản phẩm có thể không chính xác. Khách hàng có thể tham khảo giá ở trang gốc để điều chỉnh.
                    </div>
                </div>
            </div>
            @if(!Auth::guest())
            <div class="btn-add">
                <button class="btn btn-primary add-shoopingcart">Thêm vào giỏ hàng</button>
            </div>
            @endif

            @elseif(!empty($colors))
            <div class="color-box">
                <div class="col-md-2 detail-label">Màu sắc</div>
                <div class="col-md-10">
                    @foreach ($colors as $key => $c)
                    <div class="color attribute">
                        <span class="attr-name">
                            <a href="javascript:;" 
                               title="{!! $page=='1688' ? $c['colorName'] : mb_convert_encoding($c['colorName'],'UTF-8','GB2312') !!}" >
                                @if($c['colorImg'] != '')
                                <span class="attr-img">
                                    <span class="color-name" style="display:none">
                                        {!! $page=='1688' ? $c['colorName'] : mb_convert_encoding($c['colorName'],'UTF-8','GB2312') !!}
                                    </span>
                                    <img class="color-img" src="{{$c['colorImg']}}">
                                </span>
                                @else
                                <span class="color-name">
                                    {!! $page=='1688' ? $c['colorName'] : mb_convert_encoding($c['colorName'],'UTF-8','GB2312') !!}
                                </span>
                                @endif
                            </a>
                        </span>
                        <span class="color-price attr-price">
                            <input type="text" name="price" value="{{ $c['colorPrice'] }}">Tệ
                        </span>
                        <span class="color-quantity attr-quantity">Còn {{ $c['colorQuantity'] }}</span>
                        <div class="amount">
                            <button type="button" class="btn btn-default btn-number" data-type="minus" 
                                    data-field="attr_{{ $key }}" disabled>
                                <span class="glyphicon glyphicon-minus"></span>
                            </button>
                            <input type="text" class="form-control input-number" name="attr_{{ $key }}" value="0" min="0">
                            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="attr_{{ $key }}">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>
                        <div class="note">
                            <input type="text" name="note" placeholder="Ghi chú">
                        </div>
                    </div>
                    @endforeach
                    <div class="price-note">
                        Chú ý : Giá sản phẩm có thể không chính xác. Khách hàng có thể tham khảo giá ở trang gốc để điều chỉnh.
                    </div>
                </div>
            </div>
            @if(!Auth::guest())
            <div class="btn-add">
                <button class="btn btn-primary add-shoopingcart">Thêm vào giỏ hàng</button>
            </div>
            @endif

            @elseif(!empty($sizes))
            <div class="size-box">
                <div class="col-md-2 detail-label">Kích cỡ</div>
                <div class="col-md-10">
                    @foreach ($sizes as $key => $s)
                    <div class="size attribute">
                        <span class="size-name attr-name">
                            {!! $page=='1688' ? $s['sizeName'] : mb_convert_encoding($s['sizeName'],'UTF-8','GB2312') !!}
                        </span>
                        <span class="size-price attr-price">
                            <input type="text" name="price" value="{{ $s['sizePrice'] }}">Tệ
                        </span>
                        <span class="size-quantity attr-quantity">Còn {{ $s['sizeQuantity'] }}</span>
                        <div class="amount">
                            <button type="button" class="btn btn-default btn-number" data-type="minus" 
                                    data-field="attr_{{ $key }}" disabled>
                                <span class="glyphicon glyphicon-minus"></span>
                            </button>
                            <input type="text" class="form-control input-number" name="attr_{{ $key }}" value="0" min="0">
                            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="attr_{{ $key }}">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>
                        <div class="note">
                            <input type="text" name="note" placeholder="Ghi chú">
                        </div>
                    </div>
                    @endforeach
                    <div class="price-note">
                        Chú ý : Giá sản phẩm có thể không chính xác. Khách hàng có thể tham khảo giá ở trang gốc để điều chỉnh.
                    </div>
                </div>
            </div>
            <div class="btn-add">
                <button class="btn btn-primary add-shoopingcart">Thêm vào giỏ hàng</button>
            </div>

            @else
            <div class="amount-box">
                <div class="col-md-2 detail-label">Số lượng</div>
                <div class="col-md-10">
                    <button type="button" class="btn btn-default btn-number" data-type="minus" 
                            data-field="amount" disabled>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                    <input type="text" class="form-control input-number" name="amount" value="0" min="0">
                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="amount">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </div>
            </div>
            <div>
                <div class="col-md-2 detail-label">Ghi chú</div>
                <div class="col-md-10">
                    <input type="text" name="note" placeholder="Ghi chú" style="width: 300px; margin-bottom: 15px;">
                </div>
            </div>
            <div class="price-note">
                Chú ý : Giá sản phẩm có thể không chính xác. Khách hàng có thể tham khảo giá ở trang gốc để điều chỉnh.
            </div>
            @if(!Auth::guest())
            <div class="btn-add">
                <button class="btn btn-primary add-shoopingcart">Thêm vào giỏ hàng</button>
            </div>
            @endif
            @endif

            <!-- <div class="add-cart-res">Sản phẩm đã được thêm vào giỏ hàng</div> -->
            <div id="hasItem">
                <p class="has-new-item">Sản phẩm đã được thêm vào giỏ hàng</p>
                <p class="has-old-item">Bạn chưa chọn số lượng sản phẩm</p>
            </div>
        
            <!-- <div class="pay-box">
                <div>
                    <span class="pay-label">Số sản phẩm đã chọn</span>
                    <span class="total-quantity"></span>
                </div>
                <div>
                    <span class="pay-label">Số tiền phải thanh toán</span>
                    <span class="total-amount"></span>
                </div>
            </div> -->

            
        </div>
    </div>
    <div class="row product-des">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#detail"><span class="subject">Chi tiết sản phẩm</span></a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="detail" class="tab-pane fade in active">
                <div class="description">
                    @if(!empty($description))
                        @if($page=='1688')
                            @foreach ($description as $d)
                            <div class="col-md-4">
                                {{$d}}
                            </div>
                            @endforeach
                        @elseif($page=='taobao' OR $page=='tmall')
                            @foreach ($description as $d)
                            <div class="col-md-4">
                                <?php echo mb_convert_encoding($d, 'UTF-8', 'GB2312'); ?>
                            </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="fail">Hiện tại hệ thống đang bận, vui lòng thử lại sau.</div>
    @endif
</div>

@endsection
@section('scripts')
<script src="{{url('/')}}/public/assets/js/jquery-ui-1.12.1.min.js"></script>    
<script src="{{url('/')}}/public/assets/js/flytocart.js"></script>        
<script>

    $(document).ready(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".quantity").each(function(index){
            if($(this).html()==0){
                //$(".input-number").eq(index).readOnly;
            }
        });

        $('ul.color li').click(function() {  // chỉ có trong trường hợp sp có nhiều màu sắc và kích cỡ
            $('ul.color li').removeClass('li-selected');
            $(this).addClass('li-selected');          

            var page = $("input[name='page']").val();
            var sizes = $("input[name='sizes']").val();
            var skuMap = $("input[name='skuMap']").val();
            var first = $("input[name='first']").val();
            var default_price = ( page ==='1688' ? $("input[name='default_price']").val() : '');
            var colorId = $(this).attr("data-value");
            var colorName = $(this).attr("data-title");
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{url('/get-prop')}}" ,
                data: {
                    "page": page,
                    "sizes": sizes,
                    "skuMap": skuMap,
                    "first": first,
                    "default_price": default_price,
                    "colorId": colorId,
                    "colorName": colorName
                },
                success: function(res){
                    $("input[name='price']").each(function(index){
                        $(this).val(res[index].sizePrice);
                    });
                    $(".quantity").each(function(index){
                        $(this).html(res[index].sizeQuantity);
                        if($(this).html()==0){
                            $(".input-number").eq(index).attr('disabled', true);
                        }
                    });
                },
                error:function(res){
                    console.log("xay ra loi" + JSON.stringify(res));  
                }
            });            
        });


        $('.btn-number').click(function(e){
            e.preventDefault();
            fieldName = $(this).attr('data-field');
            type      = $(this).attr('data-type');
            var input = $("input[name='"+fieldName+"']");
            var currentVal = parseInt(input.val());
            if(!isNaN(currentVal)) {
                if(type == 'minus') {           
                    if(currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    } 
                    if(parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }
                }
                else if(type == 'plus') {
                    input.val(currentVal + 1).change();
                }
            } 
            else {
                input.val(0);
            }
        });
        $('.input-number').change(function() {   
            minValue =  parseInt($(this).attr('min'));
            valueCurrent = parseInt($(this).val());
            name = $(this).attr('name');
            if(valueCurrent > minValue) {
                $(".btn-number[data-field='"+name+"']").removeAttr('disabled')
            } 
        });
		
		var page = '{{ $page }}';
		var shop_name = '';
		if(page=="tmall" || page=='taobao')
			shop_name = "{!! mb_convert_encoding($shop_name, 'UTF-8', 'GB2312') !!}";
		else if(page=='1688')
			shop_name = "{{ $shop_name }}";

        $('.add-shoopingcart').click(function() {
            var quantity = 0;
            $(".input-number").each(function(){
                quantity+= $(this).val();
            });

            if(quantity==0){
                $(".has-old-item").show();
            }
            else{
                $(".has-old-item").hide();

                // các thuộc tính chung
                var link = $(".input-link").val();
                var name = $(".product-name").text();
                //var shop = $(".shop-name").text();
                var shop = shop_name;
                var image = $(".image").attr("src");
                var size = $('.size-box').length >0 ? new Array() : '';
                var color = new Array();
                var singleProduct = new Array();

                if($('.size-box').length >0){  // nếu sp có nhiều kích cỡ
                    //var color = '';
                    //var size = new Array();
                    $(".size").each(function(index){
                        var amount = $(".input-number").eq(index).val();
                        if(parseInt(amount) > 0){
                            var sizeName = $(".size-name").eq(index).text();
                            var sizePrice = $("input[name='price']").eq(index).val();
                            var note = $("input[name='note']").eq(index).val();
                            size.push({"size":sizeName, "price":sizePrice, "amount":amount, "note":note});
                        }  
                    });
                    if($('.color-box').length >0){  // nếu sp có nhiều kích cỡ và màu sắc
                        var colorName = $(".li-selected").attr("data-title");
                        var colorImg = '';
                        if($('.li-selected img').length >0)
                            colorImg = $(".li-selected img").attr("src");
                        color.push({"color":colorName, "colorImg":colorImg});
                    }

                }
                else if($('.color-box').length >0){  // nếu sp có nhiều màu sắc
                    $(".color").each(function(index){
                        var amount = $(".input-number").eq(index).val();
                        if(parseInt(amount) >0 ){
                            var colorName = $(".color-name").eq(index).text();
                            var colorImg = '';
                            if($(".color-img").eq(index).length >0)
                                colorImg = $(".color-img").eq(index).attr('src');
                            var colorPrice = $("input[name='price']").eq(index).val();
                            var note = $("input[name='note']").eq(index).val();
                            color.push({"color":colorName, "colorImg":colorImg, "price":colorPrice, "amount":amount, "note":note});
                        }                        
                    });

                }
                else{  // nếu sp không có nhiều màu sắc và kích cỡ (chỉ có 1 loại duy nhất)
                    var price = $("input[name='price']").val();
                    var amount = $(".input-number").val();
                    var note = $("input[name='note']").val();
                    singleProduct = {"price": price, "amount": amount, "note":note};
                }

                $.ajax({
                    type: "POST",
                    url: '{{ url("/cart/create") }}',
                    data: {
                        'page': page,
                        'productName': name,
                        'image': image,
                        'url': link,
                        'shop': shop,
                        'color': color,
                        'size': size,
                        'singleProduct': singleProduct
                    },
                    success: function(response) {
                        //window.location.href= '{{ url('/cart ') }}';
                        $('#cart-item-count').html("<i class='fa fa-shopping-cart shopping-cart-icon' aria-hidden='true'></i>" + "Giỏ Hàng (" + response['totalProduct'] + ")");
                        $(".has-new-item").show();
                        $(".input-number").val('0');
                    },
                    error: function (response) {
                        $(".has-old-item").hide();
                        $(".has-new-item").hide();
                        console.log("Error :" + response.error);
                    }
                });
            }  
        });
    });
</script>   
@endsection
