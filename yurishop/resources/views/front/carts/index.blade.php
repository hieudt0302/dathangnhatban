@extends('layouts.master') 
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

<hr>
<div class="container">
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
    
    <div class="row">
        <div class="col-xs-12">
            <h1 class="front-order-title">
                <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Giỏ Hàng
            </h1>
            @include('notifications.status_message') 
@include('notifications.errors_message') 
@if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
 @endif 
 @if (session()->has('error_message'))
            <div class="alert alert-danger">
                {{ session()->get('error_message') }}
            </div>
@endif
          
        </div>
    </div>
    @if(Cart::totalProduct() > 0)
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">

                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-list" style="table-layout: fixed; text-align: center;">
                        <thead>
                            
                        </thead>
                        <tbody>
                            @foreach ($groupedCartItems as $key => $items)
                            <tr class="shop-group">
                                <th colspan="2">
                                    SHOP {{$loop->iteration}}: {{ $key }}
                                </th>
                                <th colspan="2">Tổng Số Lượng:</th>
                                <th>{{ array_sum(array_column($items,'quantity')) }}</th>
                                <th colspan="2">Tổng Giá Tiền:</th>
                                <th colspan="2" class="cny-money">
                                    <p>{{ number_format(array_sum(array_column($items,'total_price')), 2, '.', ',') }}</p>
                                </th>
                            </tr>
                            <tr>
                                <td>Hình Ảnh</th>
                                <td>Tên Sản Phẩm</th>
                                <td>Màu</th>
                                <td>Size</th>
                                <td>Số Lượng</th>
                                <td>Giá Tiền</th>
								<td>Thành Tiền</th>
                                <td>Ghi chú</th>
                                <td></th>
                            </tr>
                           
                            @foreach ($items as $item)
                            <tr>
                                <td>
                                    <a href="{{ $item["url"] }}" target="_blank">
                                        <img src="{{ $item["image"] }}" alt="" class="img-rounded img-inside">
                                    </a>
                                </td>
                                
                                <td> {{ $item["name"] }}</td>
                                <td> {{ $item["color"] }} </td>
                                <td> {{ $item["size"] }} </td>
                                <td>
                                    <div class="input-group spinner">
                                        <input type="text" class="form-control quantity" data-id="{{ $item['id'] }}" 
                                               value="{{ $item['quantity'] }}" min="0" max="100000">
                                        <div class="input-group-btn-vertical">
                                            <button class="btn btn-default ajust-quantity" type="button">
                                                <i class="fa fa-caret-up"></i>
                                            </button>
                                            <button class="btn btn-default ajust-quantity" type="button">
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
								<td class="cny-money">
                                    <p>{!! is_numeric($item["unit_price"]) ? number_format($item["unit_price"], 2, '.', ',') : $item["unit_price"] !!}</p>
                                </td>
                                <td class="cny-money">
                                    <p>{!! is_numeric($item["total_price"]) ? number_format($item["total_price"], 2, '.', ',') : $item["total_price"] !!}</p>
                                </td>
                                <td style="word-wrap: break-word;"> {{ $item["note"] }} </td>
                                <td>
                                    <!-- <form action="{{ url('cart', $item['id']) }}" method="POST" class="side-by-side">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form> -->
                                    <a type="button" class="btn btn-danger" data-id="{{ $item['id'] }}" data-toggle="modal" 
                                       data-target="#modal-delete-product">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-xs-6 text-left">
                            <a href="{{url('/')}}"  class="btn btn-primary">
                                <i class="fa fa-shopping-basket"></i> Thêm sản phẩm 
                            </a>
                        </div>
                        <div class="col-xs-6 text-right">
							<a type="button" class="btn btn-danger"  data-toggle="modal" data-target="#modal-delete-carts">
								<i class="fa fa-trash"></i> Xóa Hết Giỏ Hàng
							</a> 
                            <!-- <form action="{{ url('/emptyCart') }}" method="POST"> -->
                                <!-- {!! csrf_field() !!} -->
                                <!-- <input type="hidden" name="_method" value="DELETE"> -->
                                <!-- <button type="submit" class="btn btn-danger"> -->
                                    <!-- <i class="fa fa-trash"></i> Xóa Hết Giỏ Hàng -->
                                <!-- </button> -->
                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-6"></div>
            <div class="col-xs-6 bs-example bs-cart">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="total" class="col-sm-8 control-label text-right">TỔNG SỐ TIỀN</label>
                        <div class="col-sm-4 text-right">
                            <label for="total" class="control-label cny-money">
                                <p>{{ number_format(Cart::totalMoney(), 2, '.', ',') }}</p>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 text-right">
                            <a type="button" class="btn-lg btn-primary" href="{{ url('/order/create') }}"><span class="glyphicon glyphicon-ok"></span> Đặt Hàng</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <h3 style="color: #d9534f;">@lang('shoppings.cart_noitems')</h3>
                <a href="{{url('/')}}"  class="btn btn-primary" style="margin-top: 10px; margin-bottom: 50px">
                    <i class="fa fa-shopping-basket"></i> Thêm sản phẩm
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- Modal -->
<div class="modal modal-danger fade" id="modal-delete-product">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cảnh Báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn chắc chắn muốn xóa sản phẩm này ra khỏi giỏ hàng?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Đóng</button>
                <form name="form-product-delete"  method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-danger" value="Xóa">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal modal-danger fade" id="modal-delete-carts" style="z-index:2017;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cảnh Báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có thật sự muốn xóa hết giỏ hàng không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Đóng</button>
				<form action="{{ url('/emptyCart') }}" method="POST">
                    {!! csrf_field() !!} 
                    <input type="hidden" name="_method" value="DELETE"> 
                    <button type="submit" class="btn btn-danger"> 
                        <i class="fa fa-trash"></i> Xóa Hết Giỏ Hàng 
                    </button> 
                </form> 
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection 
@section('scripts')
<script>
    (function () {
        $('.spinner .btn:first-of-type').on('click', function () {
            var btn = $(this);
            var input = btn.closest('.spinner').find('input');
            if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                input.val(parseInt(input.val(), 10) + 1);
                //update cart
                var id = input.attr('data-id');
                $.ajax({
                    type: "PATCH",
                    url: '{{ url("/cart") }}' + '/' + id + '/' + input.val(),
                    data: {
                        'quantity': input.val(),
                    },
                    success: function (data) {
                        // window.location.href = '{{ url("/cart") }}';
						location.reload();
                    },
                    error: function () {
                        alert('error');
                    }
                });
                //end update cart
            } else {
                btn.next("disabled", true);
            }
        });

        $('.spinner .btn:last-of-type').on('click', function () {
            var btn = $(this);
            var input = btn.closest('.spinner').find('input');
            if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                input.val(parseInt(input.val(), 10) - 1);
                //update cart
                var id = input.attr('data-id');
                $.ajax({
                    type: "PATCH",
                    url: '{{ url("/cart") }}' + '/' + id + '/' + input.val(),
                    data: {
                        'quantity': input.val(),
                    },
                    success: function (data) {
                        //window.location.href = '{{ url("/cart") }}';
						location.reload();
                    },
                    error: function () {
                        alert('error');
                    }
                });
                //end update cart
            } else {
                btn.prev("disabled", true);
            }
        });


        $('.quantity').focusout('input', function () {
            var id = $(this).attr('data-id')
            $.ajax({
                type: "PATCH",
                url: '{{ url("/cart") }}' + '/' + id + '/' + this.value,
                data: {
                    'quantity': this.value,
                },
                success: function (data) {
                    // window.location.href = '{{ url("/cart") }}';
					location.reload();
                },
                error: function () {
                    alert('error');
                }
            });
        });

        $('#modal-delete-product').on('shown.bs.modal', function (e) {
            var itemId = $(e.relatedTarget).data('id');
            var action = "{{ url('cart') }}/" + itemId;
            $(e.currentTarget).find('form[name="form-product-delete"]').attr("action", action);
        })  
    })();
</script>
@endsection