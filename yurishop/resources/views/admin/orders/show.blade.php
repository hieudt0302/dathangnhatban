@extends('layouts.admin') @section('title', 'Chi Tiết Đơn Hàng') @section('description', 'This is a blank description that needs to be implemented') @section('pageheader', 'Người Dùng Đặt Hàng') @section('pagedescription', 'Chi Tiết') @section('breadarea','Đơn
Đặt Hàng') @section('breaditem', 'Người Dùng') @section('content') @include('notifications.status_message') @include('notifications.errors_message') @if (session()->has('success_message'))
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
        <div class="box box-warning">
            <div class="box-header with-border">
                <i class="fa fa-list-ol"></i>
                <h3 class="box-title">Chi Tiết Đơn Hàng</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-8">  
                        <button type="button" class="btn btn-primary btn-add-product" data-toggle="collapse" 
                                href="#collapse-add-product" aria-expanded="false" aria-controls="collapse-add-product"
                                {{ (($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status != 1 AND $order->status != 2 AND $order->status != 3)) ? 'disabled' : ''}}>
                           <i class="fa fa-plus-square-o"></i>
                           <i class="fa fa-minus-square-o" style="display: none;"></i>
                           Thêm sản phẩm mới
                        </button>
                        <div class="collapse collapse-add-product" id="collapse-add-product">
                            <div class="input-group get-product-info">
                                <input name="product_link" type="text" class="form-control" placeholder="Nhập link sản phẩm"
                                       value="" autofocus="true"> 
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-product-info" title="Lấy thông tin sản phẩm">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="loading" style="display: none">
                                <img src="{{url('/')}}/public/assets/img/loading.gif" alt="Loading..." />
                            </div>
                            <div class="product-invalid" style="display: none;"></div>
                            <div class="add-product" style="display: none;">
                                <input type="hidden" class="order-id" value="{{$order->id}}">
                                <div class="col-xs-2 image"> </div>
                                <div class="col-xs-10"> 
                                    <div class="row"> 
                                        <b class="name"></b>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-xs-3 attribute-key">Cửa hàng : </div>
                                        <div class="col-xs-9 attribute-value shop"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-2 attribute-key">Giá : </div>
                                        <div class="col-xs-4 attribute-value"> 
                                            <div class="input-group">
                                                <input class="form-control price" type="text">
                                                <span class="input-group-addon" title="Giá tiền theo đồng nhân dân tệ">CNY</span> 
                                            </div>
                                        </div>
                                        <div class="col-xs-2 attribute-key">Màu sắc : </div>
                                        <div class="col-xs-4 attribute-value"> 
                                        <select class="form-control color">
                                            
                                        </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-2 attribute-key">Số lượng : </div>
                                        <div class="col-xs-4 attribute-value"> 
                                            <div class="input-group">
                                                <input class="form-control quantity" type="number" 
                                                       value="1" min="0">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 attribute-key">Kích cỡ : </div>
                                        <div class="col-xs-4 attribute-value">
                                            <select class="form-control size">
                                            
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label>Ghi chú</label>
                                            <textarea class="form-control note" rows="2" 
                                                      placeholder="Nội dung ghi chú..." disable> </textarea>
                                        </div>
                                    </div>
                                    <div class="row invalid-quantity" style="display: none;">
                                        Bạn chưa chọn số lượng sản phẩm
                                    </div>
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-save-product" title="Lưu sản phẩm">
                                            <i class="fa fa-check"></i>
                                            Lưu sản phẩm
                                        </button>
                                    </div>
                                    
                                </div>                 
                            </div>
                        </div>   
                    </div>
                    <div class="col-xs-4 text-right">
                        <div class="form-inline">
                            @if($order->status===1)
                            <!-- <form action="{{ url('/admin/orders/send/ordershop') }}/{{$order->id}}" method="POST" class="form-group">
                                    {!! method_field('patch') !!} {!! csrf_field() !!}
                                    <input type="hidden" name="freight1">
                                    <input type="hidden" name="freight2">
                                    <input type="hidden" name="weight">
                                    <input type="hidden" name="service">
                                    <input type="hidden" name="deposit">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-save"></i> Lưu &amp; Gộp Đơn Đặt Hàng
                                    </button>
                                </form> -->
                            @endif
<!--                             <form action="{{ url('/admin/orders') }}/{{$order->id}}" method="POST" class="form-group">
                                {!! method_field('patch') !!} {!! csrf_field() !!}
                                <input type="hidden" name="status">
                                <input type="hidden" name="freight1">
                                <input type="hidden" name="freight2">
                                <input type="hidden" name="weight">
                                <input type="hidden" name="service">
                                <input type="hidden" name="deposit">
                                <button class="btn btn-primary" type="submit"
                                        {{ (($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 6 OR $order->status == 7)) ? 'disabled' : ''}}>
                                    <i class="fa fa-save"></i> Lưu
                                </button>
                            </form> -->
                            <a type="button" class="btn btn-primary pull-right" style="margin-right: 5px; margin-left: 10px" 
                               href="{{ URL::to('admin/orders/export')}}/{{$order->id}}">
                                <i class="fa fa-print"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-2">
                        <p class="text-right">Tên Khách Hàng:</p>
                    </div>
                    <div class="col-xs-5">
                        <p class="text-left">{{$order->user->last_name}} {{$order->user->first_name}}</p>
                    </div>
                    <div class="col-xs-2">
                        <p class="text-right">Điện Thoại:</p>
                    </div>
                    <div class="col-xs-3">
                        <p class="text-left">{{$order->shipphone}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2">
                        <p class="text-right">Ngày Đặt Hàng:</p>
                    </div>
                    <div class="col-xs-5">
                        <p class="text-left">{{$order->created_at}}</p>
                    </div>
                    <div class="col-xs-2">
                        <p class="text-right">Trạng Thái:</p>
                    </div>
                    <div class="col-xs-3">
                        <!-- <p class="text-left">
                                @if($order->status===1)
                                <span>Chờ xử lý</span> @elseif($order->status===2)
                                <span>Đang xử lý</span> @elseif($order->status===3)
                                <span>Hoàn thành</span> @elseif($order->status===4)
                                <span>Hủy</span> @else
                                <span>Không xác định!</span> @endif
                            </p> -->
                        <div class="form-group">
                            <select id="status" name="status" type="text" class="form-control"
                                    {{ (($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 6 OR $order->status == 7)) ? 'disabled' : ''}}>
                                <option value="1" {{ $order->status===1 ? 'selected="selected"' : '' }} disabled>Chờ xử lý</option>
                                <option value="2" {{ $order->status===2 ? 'selected="selected"' : '' }}
                                        {{ $order->status===2 ? "disabled" : "" }}>
                                    Đang xử lý
                                </option>
                                <option value="3" {{ $order->status===3 ? 'selected="selected"' : '' }}
                                        {{ $order->status===3 ? "disabled" : "" }}>
                                    Chờ đặt cọc
                                </option>
                                <option value="4" {{ $order->status===4 ? 'selected="selected"' : '' }}
                                        {{ $order->status===4 ? "disabled" : "" }}>
                                    Đã đặt cọc
                                </option>
                                <option value="5" {{ $order->status===5 ? 'selected="selected"' : '' }}
                                        {{ $order->status===5 ? "disabled" : "" }}>
                                    Khiếu nại
                                </option>
                                <option value="6" {{ $order->status===6 ? 'selected="selected"' : '' }}
                                        {{ $order->status===6 ? "disabled" : "" }}>
                                    Đã hoàn thành
                                </option>
                                <option value="7" {{ $order->status===7 ? 'selected="selected"' : '' }}
                                        {{ $order->status===7 ? "disabled" : "" }}>
                                    Hủy
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2">
                        <p class="text-right">Địa Chỉ Nhận Hàng:</p>
                    </div>
                    <div class="col-xs-5">
                        <p class="text-left">{{ $order->shipaddress }}, {{ $order->shipdistrict }}, {{ $order->shipcity }}</p>
                    </div>
                    <div class="col-xs-2">
                        <p class="text-right">Lộ Trình:</p>
                    </div>
                    <div class="col-xs-3">
                        <p class="text-left" style="color:red;">
                            @if($order->statusRoute() == 1) Kho Trung Quốc
                            @elseif($order->statusRoute() == 2) Đang vận chuyển
                            @elseif($order->statusRoute() == 3) Kho Đà Nẵng
                            @else Không xác định
                            @endif
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-8 orderdetail-products">
                    @foreach($freight1details as $key => $sub )
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="well well-sm">
                                    @if(($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status != 1 AND $order->status != 2))
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x shop-icon-color"></i>
                                        <strong class="fa-stack-1x order-shop-index fa-inverse">{{$loop->iteration}}</strong>
                                    </span>                    
                                    <span class="shop-group">CỬA HÀNG: {{$sub->shop->name}}</span>
                                    @else 
                                        <span class="shop-group">CỬA HÀNG {{$loop->iteration}}:</span>
                                        <form class="shopname-form" data-id="{{$sub->shop_id}}">
                                            <!-- {!! method_field('patch') !!} {!! csrf_field() !!} -->
                                                <div class="input-group">
                                                    <input name="name"  type="text" class="form-control" value="{{$sub->shop->name}}"  placeholder="Tên cửa hàng" title="Tên cửa hàng" >
                                                    <div class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary" title="Cập nhập tên cửa hàng">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-xs-3 text-left">
                                <p style="font-weight: bold;">Lộ Trình:</p>
                            </div>
                            <div class="col-xs-9">
                                <p class="text-left" style="color:red;">
                                    @if($sub->status == 1) Kho Trung Quốc
                                    @elseif($sub->status == 2) Đang vận chuyển
                                    @elseif($sub->status == 3) Kho Đà Nẵng
                                    @else Không xác định
                                    @endif
                                </p>
                            </div>
                            
                            <div class="col-xs-3 text-left">
                                <p style="font-weight: bold;">Tổng Số Lượng:</p>
                            </div>
                            <div class="col-xs-9 item-quantity">
                            <p>
                                    <?php
                                    $shoptotalquantity = 0;
                                    foreach ($orderdetails as $key => $item) 
                                    {
                                        if($item->shop_id === $sub->shop_id)
                                        {
                                            $shoptotalquantity += $item->quantity;
                                        }
                                    }?>
                                    {{$shoptotalquantity}}
                                </p>
                            </div>
                            <div class="col-xs-3 text-left">
                                <p style="font-weight: bold;">Tổng Giá:</p>
                            </div>
                            <div class="col-xs-9 cny-money">
                                <p>
                                    <?php
                                    $shoptotalprice = 0;
                                    foreach ($orderdetails as $key => $item) 
                                    {
                                        if($item->shop_id === $sub->shop_id)
                                        {
                                            $shoptotalprice += $item->total;
                                        }
                                    }?>
                                    {{number_format($shoptotalprice, 2, '.', ',')}}
                                </p>
                            </div>
                            <div class="col-xs-3 text-left">
                                <p style="font-weight: bold;">Mã Vận Đơn:</p>
                            </div>
                            <div class="col-xs-9">
                                @if(($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status != 1 AND $order->status != 2 AND $order->status != 3 AND $order->status != 4))                  
                                    <p class="text-left">{{$sub->landingcode}}</p>
                                @else 
                                    <form class="landingcode-form" data-id="{{$sub->id}}" >
                                    <!-- {!! method_field('patch') !!} {!! csrf_field() !!} -->
                                        <div class="input-group">                                                                                           
                                            <input name="landingcode"  type="text" class="form-control" value="{{$sub->landingcode}}" placeholder="Chưa có mã vận đơn" title="Mã vận đơn" >
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn btn-primary" title="Cập nhập mã vận đơn">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <br>
                        @foreach ($orderdetails as $key => $item) 
                        @if($item->shop_id === $sub->shop_id)
                        <div class="row">
                            <div class="col-xs-2">
                                <!-- data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE0MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzE0MHgxNDAKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNWRlNTI1Y2Y5NiB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1ZGU1MjVjZjk2Ij48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjQ0LjA1NDY4NzUiIHk9Ijc0LjUiPjE0MHgxNDA8L3RleHQ+PC9nPjwvZz48L3N2Zz4= -->
                                <a href="{{$item->url}}" target="_blank"><img src="{{$item->image}}"
                                        alt="default" class="img-rounded img-inside">
                                    </a>
                            </div>
                            <div class="col-xs-10">
                                <div class="row">
                                    <div class="col-xs-12 text-left">
                                        <p>{{ $item->productname }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2 text-left">
                                        <p>Giá:</p>
                                    </div>
                                    <div class="col-xs-4 text-left" style="padding-left: 0">
                                        @if(($order->usercreated == null OR $order->usercreated == Auth::user()->id) AND ($order->status == 1 OR $order->status == 2))
                                        <form class="unitprice-form" data-id="{{$order->id}}" >
                                            <!-- {!! method_field('patch') !!} {!! csrf_field() !!} -->
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary" title="Cập nhập giá tiền sản phẩm">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="orderdetail_id" value="{{$item->id}}">
                                                <input name="unitprice" type="text" class="form-control" 
                                                        placeholder="0.00" value="{{ $item->unitprice }}" 
                                                       title="{{ $item->unitprice }}" step="any">
                                                <span class="input-group-addon" title="Giá tiền theo đồng nhân dân tệ">CNY</span>
                                            </div>
                                        </form>
                                        <br>
                                        @else
                                        <div class="cny-money">
                                            <p title="Giá tiền theo đồng nhân dân tệ">{{ number_format($item->unitprice, 2, '.', ',') }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-xs-2">
                                        <p>Kích Cỡ:</p>
                                    </div>
                                    <div class="col-xs-4 item-size" style="padding-left: 0">
                                        @if(($order->usercreated == null OR $order->usercreated == Auth::user()->id) AND ($order->status == 1 OR $order->status == 2))
                                        <form class="size-form" data-id="{{$order->id}}" >
                                            <!-- {!! method_field('patch') !!} {!! csrf_field() !!} -->
                                            <div class="input-group">
                                                <input type="hidden" name="orderdetail_id" value="{{$item->id}}">
                                                <input name="size" type="text" class="form-control" value="{{ $item->size }}" > 
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary" title="Cập nhập kích cỡ sản phẩm">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <br> 
                                        @else
                                        <div class="item-size">
                                            <p title="Kích cỡ sản phẩm">{{ $item->size }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2 text-left ">
                                        <p>Số Lượng:</p>
                                    </div>
                                    <div class="col-xs-4 text-left" style="padding-left: 0">
                                        @if(($order->usercreated == null OR $order->usercreated == Auth::user()->id) AND ($order->status == 1 OR $order->status == 2))
                                        <form class="quantity-form" data-id="{{$order->id}}" >
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary" title="Cập nhập số lượng sản phẩm">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="orderdetail_id" value="{{$item->id}}">
                                                <input name="quantity" type="number" class="form-control" value="{{ $item->quantity }}" step="1">
                                            </div>
                                        </form>
                                        @else
                                        <div class="item-quantity">
                                            <p title="Số lượng sản phẩm">{{ $item->quantity }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-xs-2">
                                        <p>Màu Sắc:</p>
                                    </div>
                                    <div class="col-xs-4 item-color" style="padding-left: 0">
                                        @if(($order->usercreated == null OR $order->usercreated == Auth::user()->id) AND ($order->status == 1 OR $order->status == 2))
                                        <form class="color-form" data-id="{{$order->id}}" >
                                            <!-- {!! method_field('patch') !!} {!! csrf_field() !!} -->
                                            <div class="input-group">
                                                <input type="hidden" name="orderdetail_id" value="{{$item->id}}">
                                                <input name="color" type="text" class="form-control" value="{{ $item->color }}" > 
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary" title="Cập nhập màu sắc sản phẩm">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <br>
                                        @else
                                        <div class="item-color">
                                            <p title="Màu sắc sản phẩm">{{ $item->color }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- <div class="col-xs-2">
                                        <div class="form-group">
                                            <label title="Đánh dấu số lượng sản phẩm đã có đủ!">
                                                    @if($item->is_available)
                                                    <input class="is-available" data-id="{{$item->id}}" type="checkbox" checked >  Đã có
                                                    @else
                                                    <input class="is-available" data-id="{{$item->id}}" type="checkbox"> Đã có
                                                    @endif
                                                </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 text-left">
                                        @if(!empty($item->ordershop_id))
                                        <a href="{{url('/admin/ordershops',[$item->ordershop_id])}}" target="_blank"><i class="fa fa-check-square-o"></i> Thuộc đơn hàng</a> @endif
                                    </div> -->
                                    @if(($order->usercreated == null OR $order->usercreated == Auth::user()->id) AND ($order->status == 1 OR $order->status == 2))
                                    <div class="col-xs-12 text-right">
                                        <form action="{{ url('/admin/orders/item/destroy', $item->id) }}" method="POST">
                                            {!! method_field('patch') !!} {!! csrf_field() !!}
                                            <input type="hidden" name="order_id" value="{{$order->id}}">
                                            <input type="submit" class="btn btn-danger btn-sm" value="Xóa">
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        @if(!empty($item->note))
                                        <span><a data-toggle="collapse" href="#collapse{{$item->id}}-note" aria-expanded="false" aria-controls="collapse{{$item->id}}-note">Ghi chú của khách hàng</a></span>
                                        @endif
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="collapse" id="collapse{{$item->id}}-note">
                                            <div class="form-group">
                                                <textarea class="form-control" rows="4" placeholder="Không có ghi chú..." disabled>{{$item->note}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        @if(!empty($item->feedback))
                                        <span><a data-toggle="collapse" href="#collapse{{$item->id}}FeedBack" aria-expanded="false" aria-controls="collapse{{$item->id}}FeedBack">Khiếu nại của khách hàng</a></span>
                                        @endif
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="collapse" id="collapse{{$item->id}}FeedBack">
                                            <div class="form-group">
                                                <textarea class="form-control" rows="4" placeholder="Chưa có khiếu nại..." disabled>{{$item->feedback}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <hr> 
					@endif @endforeach @endforeach
                     </div>
                    <hr> 
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Tổng giá hàng:</p>
                            </div>
                            <div class="col-xs-7 text-left cny-money">
                                <p>{{number_format($order->totalamount, 2, '.', ',') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Tỷ giá:</p>
                            </div>
                            <div class="col-xs-7 text-left vnd-money">
                                <p>{{number_format($order->rate, 2, '.', ',')}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Quy đổi thành:</p>
                            </div>
                            <div class="col-xs-7 text-left vnd-money">
                                <p>{{ number_format($order->convertTotalAmountToVietNamDong(), 2, '.', ',') }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-5 center-block">
                                <p title="Phí vận chuyển nội địa Trung Quốc">Phí ship TQ:</p>
                            </div>
                            <div class="col-xs-7 text-left">
                                <div class="cny-money">
                                    <p>{{number_format($order->freight1, 2, '.', ',')}}</p>
                                </div>
                            </div>
                            <div class="col-xs-5">
                                <p>Quy đổi thành:</p>
                            </div>
                            <div class="col-xs-7 text-left vnd-money">
                                <p>{{ number_format($order->convertFreight1ToVietNamDong(), 2, '.', ',') }}</p>
                            </div>
                            <div class="col-xs-12">
                                <span><a data-toggle="collapse" href="#collapseFreight1Detail" aria-expanded="false" aria-controls="collapseFreight1Detail">Chi tiết phí ship Trung Quốc</a></span>
                            </div>
                            <div class="col-xs-12">
                                <div class="collapse in" id="collapseFreight1Detail">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <td>Cửa Hàng</td>
                                                <td>Phí</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($freight1details as $key => $sub)
                                            <tr>
                                                <td>{{str_limit($sub->shop->name, $limit  = 20, $end = '...')}}</td>
                                                <!-- <td>{{$sub->freight1_sub}}</td> -->
                                                <td>
                                                    <form class="freight1detail-form" data-id="{{$order->id}}">
                                                        {!! method_field('patch') !!} {!! csrf_field() !!}
                                                        <div class="input-group">
                                                            <div class="input-group-btn">
                                                                <button type="submit" class="btn btn-primary" 
                                                                        title="Cập nhập phí ship Trung Quốc theo cửa hàng"
                                                                         {{ (($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 7)) ? 'disabled' : ''}}>
                                                                <i class="fa fa-check"></i>
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="freight1detail_id" value="{{$sub->id}}">
                                                            <input name="unitprice" type="text" class="form-control" 
                                                                   value="{{number_format($sub->freight1_sub, 2, ',', '.')}}" 
                                                                   placeholder ="0.00" 
                                                                   title="{{number_format($sub->freight1_sub, 2, ',', '.')}}"
                                                                   {{ (($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 7)) ? 'readonly' : ''}}>
                                                            <span class="input-group-addon">CNY</span>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Trọng Lượng:</p>
                            </div>
                            <div class="col-xs-7 text-left">
                                @if(($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 7))
                                <div class="vnd-money">
                                    <p>{{$order->weight}}</p>
                                </div>
                                @else
                                <div class="input-group">
                                    <input id="weight" type="text" class="form-control" 
                                           value="{{number_format($order->weight, 2, '.', ',')}}"
                                           placeholder="0.00" title="{{number_format($order->weight, 2, '.', ',')}}">
                                    <span class="input-group-addon">KG</span>
                                </div><br> 
                                @endif
                            </div>
                            <div class="col-xs-5">
                                <p>Cước vận chuyển:</p>
                            </div>
                            <div class="col-xs-7 text-left">
                                @if(($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 7))
                                <div class="vnd-money">
                                    <p>{{$order->freight2}}</p>
                                </div>
                                @else
                                <div class="input-group">
                                    <input id="freight2" type="text" class="form-control" 
                                           value="{{number_format($order->freight2, 2, '.', ',')}}" placeholder="0.00" 
                                           title="{{number_format($order->freight2, 2, '.', ',')}}" >
                                    <span class="input-group-addon">VND</span>
                                </div><br> 
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Phí dịch vụ:</p>
                            </div>
                            <div class="col-xs-7 text-left">
                                @if(($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 7))
                                <div class="vnd-money">
                                    <p>{{$order->service}}</p>
                                </div>
                                @else
                                <div class="input-group">
                                    <input id="service" type="text" class="form-control" 
                                           value="{{number_format($order->service, 2, '.', ',')}}" placeholder="0.00" 
                                           title="{{number_format($order->service, 2, '.', ',')}}">
                                    <span class="input-group-addon">%</span>
                                </div><br> 
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Thành tiền:</p>
                            </div>
                            <div class="col-xs-7 text-left vnd-money">
                                <p>{{ number_format($order->getFinalPrice(), 2, '.', ',') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Đặt cọc:</p>
                            </div>
                            <div class="col-xs-7 text-left">
                                @if(($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 7))
                                <div class="vnd-money">
                                    <p>{{$order->deposit}}</p>
                                </div>
                                @else
                                <div class="input-group">
                                    <input id="deposit" type="text" class="form-control" 
                                           value="{{number_format($order->deposit, 2, '.', ',')}}" placeholder="0.00" 
                                           title="{{number_format($order->deposit, 2, '.', ',')}}">
                                    <span class="input-group-addon">VND</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Còn lại:</p>
                            </div>
                            <div class="col-xs-7 text-left vnd-money">
                                <p>{{ number_format($order->getDebtPrice(), 2, '.', ',') }}</p>
                            </div>
                        </div>
                        <!-- QUICK SAVE -->
<!--                         <div class="row">
                            <div class="col-xs-12  text-right">
                                <div class="form-inline">                           
                                    <form action="{{ url('/admin/orders') }}/{{$order->id}}" method="POST" class="form-group">
                                        {!! method_field('patch') !!} {!! csrf_field() !!}
                                        <input type="hidden" name="status">
                                        <input type="hidden" name="freight1">
                                        <input type="hidden" name="freight2">
                                        <input type="hidden" name="weight">
                                        <input type="hidden" name="service">
                                        <input type="hidden" name="deposit">
                                        <button class="btn btn-primary" type="submit" 
                                                {{ (($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 6 OR $order->status == 7)) ? 'disabled' : ''}}>
                                            <i class="fa fa-save"></i> 
                                            Lưu
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label>Ghi chú của khách hàng</label>
                    <textarea class="form-control" rows="4" placeholder="Nội dung ghi chú..." disabled>{{$order->note}}</textarea>
                </div>
                <div class="form-group">
                    <label>Phản hồi của khách hàng</label>
                    <textarea class="form-control" rows="4" placeholder="Nội dung phẩn hồi..." disabled>{{$order->feedback}}</textarea>
                </div>

                <div class="form-inline text-right">
                    @if($order->status===1)
                    <!-- <form action="{{ url('/admin/orders/send/ordershop') }}/{{$order->id}}" method="POST" class="form-group">
                                {!! method_field('patch') !!} {!! csrf_field() !!}
                                <input type="hidden" name="freight1">
                                <input type="hidden" name="freight2">
                                <input type="hidden" name="weight">
                                <input type="hidden" name="service">
                                <input type="hidden" name="deposit">
                                <button class="btn-lg btn-primary" type="submit">
                                    <i class="fa fa-save"></i> Lưu &amp; Gộp Đơn Đặt Hàng
                                </button>
                            </form> -->
                    @endif
                    <form action="{{ url('/admin/orders') }}/{{$order->id}}" method="POST" class="form-group">
                        {!! method_field('patch') !!} {!! csrf_field() !!}
                        <input type="hidden" name="status">
                        <input type="hidden" name="freight1">
                        <input type="hidden" name="freight2">
                        <input type="hidden" name="weight">
                        <input type="hidden" name="service">
                        <input type="hidden" name="deposit">
                        <button class="btn-lg btn-primary" type="submit" 
                                {{ (($order->usercreated != null AND $order->usercreated != Auth::user()->id) OR ($order->status == 6 OR $order->status == 7)) ? 'disabled' : ''}}>
                            <i class="fa fa-save"></i> 
                            Lưu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')

<!-- bootstrap datepicker -->
<script src="{{url('/')}}/public/assets/js/datepicker/bootstrap-datepicker.min.js"></script>

<!-- DataTables -->
<script src="{{url('/')}}/public/assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/public/assets/js/datatables/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function() {

        $('#fromDate, #toDate').datepicker({
            autoclose: true,
            todayHighlight: true,
            orientation: 'bottom',
            format: 'yyyy-mm-dd',
        });


        $("#dasboard").removeClass("active");
        $("#order").addClass("active");
        $("#user").removeClass("active");
        $("#setting").removeClass("active");

        /* Ajust Quantity of item */
        // $('.spinner .btn:first-of-type').on('click', function() {
        //     var btn = $(this);
        //     var input = btn.closest('.spinner').find('input');
        //     if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
        //         input.val(parseInt(input.val(), 10) + 1);
        //         //update quantity
        //         var id = input.attr('data-id');
        //         $.ajax({
        //             type: "PATCH",
        //             url: '{{ url("/admin/orders/ajust/quantity/item") }}' + '/' + id + '/' + {{$order->id}}+ '/' + input.val(),
        //             contentType: "application/x-www-form-urlencoded",
        //             data: {
        //                 'order_id': {{$order->id}},
        //                 'quantity': input.val(),
        //             },
        //             success: function(response) {
        //                 location.reload();
        //             },
        //             error: function(textStatus, errorThrown) {
        //                 location.reload();
        //             }
        //         });
        //         //end update input
        //     } else {
        //         btn.next("disabled", true);
        //     }
        // });

        // $('.spinner .btn:last-of-type').on('click', function() {
        //     var btn = $(this);
        //     var input = btn.closest('.spinner').find('input');
        //     if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
        //         input.val(parseInt(input.val(), 10) - 1);
        //         //update quantity
        //         var id = input.attr('data-id');
        //         $.ajax({
        //             type: "PATCH",
        //             url: '{{ url("/admin/orders/ajust/quantity/item") }}' + '/' + id + '/' + {{$order->id}} + '/' + input.val(),
        //             contentType: "application/x-www-form-urlencoded",
        //             data: {
        //                 'order_id': {{$order->id}},
        //                 'quantity': input.val(),
        //             },
        //             success: function(data) {
        //                 location.reload();
        //             },
        //             error: function(textStatus, errorThrown) {
        //                 location.reload();
        //             }
        //         });
        //         //end update input
        //     } else {
        //         btn.prev("disabled", true);
        //     }
        // });

        // $('.quantity').focusout('input', function() {
        //     var id = $(this).attr('data-id')
        //     $.ajax({
        //         type: "PATCH",
        //         url: '{{ url("/admin/orders/ajust/quantity/item") }}' + '/' + id + '/' + {{$order->id}} + '/' + this.value,
        //         data: {
        //             'order_id': {{$order->id}},
        //             'quantity': this.value,
        //         },
        //         success: function(data) {
        //             location.reload();
        //         },
        //         error: function(textStatus, errorThrown) {
        //             location.reload();
        //         }
        //     });
        // });


        $('#status').on('change', function() {
            var status = this.value;
            $('form input[name=status]').val(status);
            $('form button').prop('disabled', false);
        });

        $('#freight1').on('input', function() {
            var freight1 = this.value;
            $('form input[name=freight1]').val(freight1);
            $('form button').prop('disabled', false);
        });

        $('#freight2').on('input', function() {
            var freight2 = this.value;
            $('form input[name=freight2]').val(freight2);
            $('form button').prop('disabled', false);
        });

        $('#weight').on('input', function() {
            var weight = this.value;
            $('form input[name=weight]').val(weight);
            $('form button').prop('disabled', false);
        });

        $('#service').on('input', function() {
            var service = this.value;
            $('form input[name=service]').val(service);
            $('form button').prop('disabled', false);
        });

        $('#deposit').on('input', function() {
            var deposit = this.value;
            $('form input[name=deposit]').val(deposit);
            $('form button').prop('disabled', false);
        });
      
        //update shop name
        $('.shopname-form').on('submit', function(e) {
            e.preventDefault(); 
            var name = $(this).find('input[name=name]').val();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "PATCH",
                url: '{{ url("/admin/orders/shop") }}' + '/' + id + '/' + name + '/ajust/name',
                // data: $(this).serialize(),
                success: function(msg) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    location.reload();
                }
            });
        });
        //update landingcode
        $('.landingcode-form').on('submit', function(e) {
            e.preventDefault(); 
            var id = $(this).attr('data-id');
            var code = $(this).find('input[name=landingcode]').val();
            $.ajax({
                type: "POST",
                url: '{{ url("/admin/orders") }}/' + id + '/adjust_landingcode',
                data: { 'code' : code },
                success: function(msg) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    location.reload();
                }
            });
        });

        //update unitprice
        $('.unitprice-form').on('submit', function(e) {
            e.preventDefault(); 
            var id = $(this).attr('data-id');
            var orderdetail_id = $(this).find('input[name=orderdetail_id]').val();
            var unitprice = $(this).find('input[name=unitprice]').val();
            if (unitprice == '')
                unitprice = 0;
            $.ajax({
                type: "PATCH",
                url: '{{ url("/admin/orders") }}' + '/' + id  + '/' + orderdetail_id + '/' + unitprice + '/item/ajust/unitprice',
                // data: $(this).serialize(),
                success: function(msg) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    location.reload();
                }
            });
        });

        //update quantity
        $('.quantity-form').on('submit', function(e) {
            e.preventDefault(); 
            var id = $(this).attr('data-id');
            var orderdetail_id = $(this).find('input[name=orderdetail_id]').val();
            var quantity = $(this).find('input[name=quantity]').val();
            if(quantity == '')
                quantity = 0;
            $.ajax({
                type: "PATCH",
                url: '{{ url("/admin/orders") }}' + '/' + id  + '/' + orderdetail_id + '/' + quantity + '/item/adjust/quantity',
                // data: $(this).serialize(),
                success: function(msg) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    //location.reload();
                    alert('error');
                }
            });
        });

        //update size
        $('.size-form').on('submit', function(e) {
            e.preventDefault(); 
            var id = $(this).attr('data-id');
            var orderdetail_id = $(this).find('input[name=orderdetail_id]').val();
            var size = $(this).find('input[name=size]').val();
            $.ajax({
                type: "POST",
                url: '{{ url("/admin/orders") }}/' + id +'/' + orderdetail_id + '/adjust_size',
                data: { 'size' : size },
                success: function(msg) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    //location.reload();
                    alert('error');
                }
            });
        });

        //update color
        $('.color-form').on('submit', function(e) {
            e.preventDefault(); 
            var id = $(this).attr('data-id');
            var orderdetail_id = $(this).find('input[name=orderdetail_id]').val();
            var color = $(this).find('input[name=color]').val();
            if (color == '') 
                color = 'null';
            $.ajax({
                type: "PATCH",
                url: '{{ url("/admin/orders") }}' + '/' + id  + '/' + orderdetail_id + '/' + color + '/item/adjust/color',
                // data: $(this).serialize(),
                success: function(msg) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    location.reload();
                }
            });
        });


        //update freight1_sub
        //action="{{ url('/admin/orders/item')}}/{{$order->id}}/ajust/freight1/sub" method="POST"
        $('.freight1detail-form').on('submit', function(e) {
            e.preventDefault(); 
            var id = $(this).attr('data-id');
            var freight1detail_id = $(this).find('input[name=freight1detail_id]').val();
            var unitprice = $(this).find('input[name=unitprice]').val();
            $.ajax({
                type: "PATCH",
                url: '{{ url("/admin/orders/item") }}' + '/' + id  + '/' + freight1detail_id + '/' + unitprice + '/ajust/freight1/sub',
                // data: $(this).serialize(),
                success: function(msg) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    location.reload();
                }
            });
        });

         $('#collapse-add-product').on('shown.bs.collapse', function () {
            $(".btn-add-product .fa-plus-square-o").hide();
            $(".btn-add-product .fa-minus-square-o").show();
        });

        $('#collapse-add-product').on('hidden.bs.collapse', function () {
            $(".btn-add-product .fa-plus-square-o").show();
            $(".btn-add-product .fa-minus-square-o").hide();
        });

        // get product information
        $(".btn-product-info").on("click", function(){
            $(".get-product-info").hide();
            $(".loading").show();
            $.ajax({
                type: "POST",
                url: '{{ url("/admin/orders/product-info") }}',
                data: { 
                    'url': $("input[name='product_link']").val(),
                },
                success: function(res) {
                    $(".loading").hide();
                    if (res.success == false) {
                        $(".get-product-info").show();
                        $(".product-invalid").show();
                        if(res.url.indexOf('.taobao.com') !== -1 || res.url.indexOf('.tmall.com') !== -1 || res.url.indexOf('.1688.com') !== -1) {
                            $(".product-invalid").html("Không thể lấy được thông tin sản phẩm. Hãy thử link khác.");
                        }
                        else {
                            $(".product-invalid").html("Sản phẩm không tồn tại. Hãy thử link khác.");
                        }
                    }
                    else {
                        $(".product-invalid").hide();
                        $(".add-product").show();

                        $(".add-product .image").html("<a target=_blank href="+res.url+" title="+res.name+">"
                                                        +"<img src="+res.image+" class=img-responsive alt="+res.name+">"
                                                     +"</a>"
                        );
                        $(".add-product .shop").html(res.shop);
                        $(".add-product .name").html(res.name);
                        $(".add-product .price").val(res.price);

                        var color = '';
                        for (var i=0; i<res.color.length; ++i){
                            color += "<option value="+res.color[i]['colorName']+">"+res.color[i]['colorName']+"</option>";
                        }
                        $(".add-product .color").html(color);

                        var size = '';
                        for (var j=0; j<res.size.length; ++j){
                            size += "<option value="+res.size[j]['sizeName']+">"+res.size[j]['sizeName']+"</option>";
                        }
                        $(".add-product .size").html(size);
                    }
                },
                error: function(textStatus, errorThrown) {
                    alert('mission failed !');
                }
            });

        });
       

        //add new product
        $('.btn-save-product').on('click', function(e) {
            e.preventDefault(); 
            var order_id = $(".add-product .order-id").val();
            var quantity = $(".add-product .quantity").val();
            if(Number.isInteger(parseFloat(quantity)) == false || parseInt(quantity) == 0){
                $(".invalid-quantity").show();
            }
            else{
                $(".invalid-quantity").hide();
                $.ajax({
                    type: "PATCH",
                    url: '{{ url("/admin/orders") }}' + '/' + order_id  + '/' + 'add-product',
                    data: {
                        //'order_id': order_id,
                        'url': $(".add-product .image a").attr('href'),
                        'image': $(".add-product .image img").attr('src'),
                        'name': $(".add-product .name").text(),
                        'shop': $(".add-product .shop").text(),
                        'price': $(".add-product .price").val(),
                        'color': $('.add-product .color option:selected').text(),
                        'size': $('.add-product .size option:selected').text(),
                        'quantity': quantity,
                        'note': $(".add-product .note").val(),
                    },
                    success: function(msg) {
                        location.reload();
                    },
                    error: function(textStatus, errorThrown) {
                        location.reload();
                    }
                });
            }
            
        });

    });
</script>
@endsection