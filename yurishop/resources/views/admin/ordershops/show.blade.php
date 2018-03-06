@extends('layouts.admin') 
@section('title', 'Chi Tiết - Đơn Hàng Cửa Hàng')
@section('description', 'This is a blank description that needs to be implemented') 
@section('pageheader', 'Đặt Hàng Theo Cửa Hàng') 
@section('pagedescription', 'Chi Tiết') 
@section('breadarea','Đơn Đặt Hàng') 
@section('breaditem', 'Cửa Hàng') 
@section('content')
@include('notifications.status_message') 
@include('notifications.errors_message')
@if (session()->has('success_message'))
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
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-list-ol"></i>
                <h3 class="box-title">Chi Tiết Đơn Hàng</h3>

                <!-- <button type="submit" class="btn btn-primary pull-right save-change"><i class="fa fa-save"></i> Lưu Thay Đổi</button> -->
                <form action="{{ url('/admin/ordershops') }}/{{$ordershop->id}}" method="POST" >
                                {!! method_field('patch') !!} {!! csrf_field() !!}
                                <input type="hidden" name="freight1">
                                <input type="hidden" name="freight2">
                                <input type="hidden" name="note">
                                <input type="hidden" name="landingcode">
                                <input type="hidden" name="status">
                                <button class="btn btn-primary pull-right" type="submit" disabled>
                                    <i class="fa fa-save"></i> Lưu Thay Đổi
                                </button>
                </form>
                <a type="button" class="btn btn-primary pull-right" style="margin-right: 5px;" href="{{ URL::to('admin/ordershops/export')}}/{{$ordershop->id}}/xlsx">
                    <i class="fa fa-print"></i> Export
                </a>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <p class="text-right">Tên Cửa Hàng:</p>
                    </div>
                    <div class="col-xs-5">
                    @if($ordershop->status===3 || $ordershop->status===4)                    
                        <p class="text-left">{{$ordershop->shop->name}}</p>
                    @else 
                    <form action="{{ url('/admin/ordershops/shop')}}/{{$ordershop->id}}/ajust/name" method="POST">
                    {!! method_field('patch') !!} {!! csrf_field() !!}
                        <div class="input-group">                                                                                           
                            <input name="name"  type="text" class="form-control" value="{{$ordershop->shop->name}}" placeholder="Tên cửa hàng" title="Tên cửa hàng">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary" title="Cập nhập tên cửa hàng">
                                    <i class="fa fa-check"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                    </div>
                    <div class="col-xs-2">
                        <p class="text-right">Mã Vận Đơn:</p>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <input id="landingcode" type="text"  class="form-control" value="{{$ordershop->landingcode}}" placeholder="Mã vận đơn">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2">
                        <p class="text-right">Ngày Đặt Hàng:</p>
                    </div>
                    <div class="col-xs-5">
                        <p class="text-left">{{$ordershop->orderdate}}</p>
                    </div>
                    <div class="col-xs-2">
                        <p class="text-right">Trạng Thái:</p>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <select id="status" name="status" type="text" class="form-control">
                                <option value="1" @if($ordershop->status===1)<?php echo('selected="selected"')?>@endif>Chờ xử lý</option>
                                <option value="2" @if($ordershop->status===2)<?php echo('selected="selected"')?>@endif>Đang xử lý</option>
                                <option value="3" @if($ordershop->status===3)<?php echo('selected="selected"')?>@endif>Hoàn Thành</option>
                                <option value="4" @if($ordershop->status===4)<?php echo('selected="selected"')?>@endif>Hủy</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2">
                        <p class="text-right">Ngày Giao Hàng:</p>
                    </div>
                    <div class="col-xs-5">
                        <p class="text-left">{{$ordershop->shippeddate}}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-8 orderdetail-products">
                        @foreach($orderdetails as $key => $item)
                        <div class="row">
                            <div class="col-xs-2">
                                <!-- <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE0MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzE0MHgxNDAKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNWRlNTI1Y2Y5NiB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1ZGU1MjVjZjk2Ij48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjQ0LjA1NDY4NzUiIHk9Ijc0LjUiPjE0MHgxNDA8L3RleHQ+PC9nPjwvZz48L3N2Zz4="
                                    alt="default" class="img-rounded img-inside"> -->
                                <a href="{{$item->url}}" target="_blank"><img src="{{$item->image}}"
                                    alt="default" class="img-rounded img-inside">
                                </a>
                            </div>
                            <div class="col-xs-10">
                                <div class="row">
                                    <div class="col-xs-12 text-left">
                                        <a href="{{$item->url}}" target="_blank">
                                            <p>{{$item->productname}}</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2 text-left">
                                        <p>Giá:</p>
                                    </div>
                                    <div class="col-xs-4 text-left cny-money">
                                        <p title="Giá tiền theo đồng nhân dân tệ">{{ number_format($item->unitprice, 2, ',', '.') }}</p>
                                    </div>
                                    <div class="col-xs-2">
                                        <p>Kích Cỡ:</p>
                                    </div>
                                    <div class="col-xs-4 item-size">
                                        <p>{{$item->size}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2 text-left">
                                        <p>Số Lượng:</p>
                                    </div>
                                    <div class="col-xs-4 text-left item-quantity">
                                        <p>{{$item->quantity}}</p>
                                    </div>
                                    <div class="col-xs-2">
                                        <p>Màu Sắc:</p>
                                    </div>
                                    <div class="col-xs-4 item-color">
                                        <p>{{$item->color}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2 text-left">
                                        <p>Người Đặt:</p>
                                    </div>
                                    <div class="col-xs-4 text-left">
                                        <a href="{{ route('admin.orders.show', $item->order->id) }}" target="_blank" title="Chi tiết đơn hàng của khách hàng">
                                            <p>{{$item->order->user->last_name}} {{$item->order->user->first_name}}</p>
                                        </a>
                                    </div>
                                    <div class="col-xs-2">
                                        <p title="Số lượng sản phẩm này đã có đủ!">Đã Có:</p>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label title="Đánh dấu sản phẩm đã có đủ số lượng.">
                                                @if($item->is_available)
                                                <input class="is-available" data-id="{{$item->id}}" type="checkbox" checked>
                                                @else
                                                <input class="is-available" data-id="{{$item->id}}" type="checkbox">
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xs-2 text-left">
                                <button type="button" class="btn btn-danger" title="Xóa sản phẩm này khỏi đơn hàng!">
                                    <i class="fa fa-ban"></i>
                                </button>
                            </div> -->
                        </div>
                        <hr>
                        @endforeach
                    </div>
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-5 ">
                                <p>Tổng giá hàng:</p>
                            </div>
                            <div class="col-xs-7 text-left cny-money">
                                <p title="Đồng nhân dân tệ">{{number_format($ordershop->totalamount, 2, ',', '.')}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Phí Ship Trung Quốc:</p>
                            </div>
                            <div class="col-xs-7 text-left">
                                @if($ordershop->status===3 || $ordershop->status===4)
                                <div class="vnd-money">
                                    <p>{{number_format($ordershop->freight1, 2, ',', '.')}}</p>
                                </div>
                                @else
                                <div class="input-group">
                                    <input id="freight1" type="text" class="form-control" value="{{number_format($ordershop->freight1, 2, ',', '.')}}" placeholder="0.00"
                                        title="{{number_format($ordershop->freight1, 2, ',', '.')}}">
                                    <span class="input-group-addon" title="Đồng nhân dân tệ">CNY</span>
                                </div><br> 
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Phí Ship Việt Nam:</p>
                            </div>
                            <div class="col-xs-7 text-left">
                                @if($ordershop->status===3 || $ordershop->status===4)
                                <div class="vnd-money">
                                    <p>{{number_format($ordershop->freight2, 2, ',', '.')}}</p>
                                </div>
                                @else
                                <div class="input-group">
                                    <input id="freight2" type="text" class="form-control" value="{{number_format($ordershop->freight2, 2, ',', '.')}}" placeholder="0.00"
                                        title="{{number_format($ordershop->freight2, 2, ',', '.')}}">
                                    <span class="input-group-addon" title="Đồng nhân dân tệ">CNY</span>
                                </div><br> 
                                @endif
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-xs-5">
                                <p>Thành tiền:</p>
                            </div>
                            <div class="col-xs-7 text-left cny-money">
                                <p title="Đồng nhân dân tệ">{{number_format($ordershop->getFinalPrice(), 2, ',', '.')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Ghi chú</label>
                            <textarea id="note" class="form-control" rows="4"  @if($ordershop->status===3)<?php echo('disabled')?>@endif placeholder="Nội dung ghi chú...">{{$ordershop->note}}</textarea>
                        </div>
                      
                        <a type="button" class="btn-lg btn-primary pull-left" href="{{ URL::to('admin/ordershops/export')}}/{{$ordershop->id}}/xlsx">
                            <i class="fa fa-print"></i> Export
                        </a>
                        <form action="{{ url('/admin/ordershops') }}/{{$ordershop->id}}" method="POST" class="form-group">
                                {!! method_field('patch') !!} {!! csrf_field() !!}
                                <input type="hidden" name="freight1">
                                <input type="hidden" name="freight2">
                                <input type="hidden" name="note">
                                <input type="hidden" name="landingcode">
                                <input type="hidden" name="status">
                                <button class="btn-lg btn-primary pull-right" type="submit" disabled>
                                    <i class="fa fa-save"></i> Lưu Thay Đổi
                                </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('scripts')

<!-- bootstrap datepicker -->
<script src="{{url('/')}}/public/assets/js/datepicker/bootstrap-datepicker.min.js"></script>

<!-- DataTables -->
<script src="{{url('/')}}/public/assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/public/assets/js/datatables/dataTables.bootstrap.min.js"></script>


<script>
    $(document).ready(function () {
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


        $('#status').on('change', function() {
            var status = this.value;
            $('form input[name=status]').val(status);
            $('form button').prop('disabled', false);
        });

        $('#freight1').on('input', function () {
            var freight1 = this.value;
            $('form input[name=freight1]').val(freight1);
            $('form button').prop('disabled', false);
        });

        $('#freight2').on('input', function () {
            var freight2 = this.value;
            $('form input[name=freight2]').val(freight2);
            $('form button').prop('disabled', false);
        });

        $('#note').on('input', function () {
            var service = this.value;
            $('form input[name=note]').val(service);
            $('form button').prop('disabled', false);
        });

        $('#landingcode').on('input', function () {
            var deposit = this.value;
            $('form input[name=landingcode]').val(deposit);
            $('form button').prop('disabled', false);
        });


        $('.is-available').on('click', function () {
             var id = $(this).attr('data-id');
            // var is_available = 0;
            
            // if($(this).is(":checked")){
            //     is_available =1;
            // }

            $.ajax({
                    type: "PATCH",
                    url: '{{ url("/admin/ordershops/ajust/available") }}' + '/' + id,
                    data: {
                        // 'is_available':is_available
                    },
                    success: function (data) {
                         location.reload();
                    },
                    error: function (textStatus, errorThrown) {
                         location.reload();
                    }
                });
        });
    });
</script>
@endsection