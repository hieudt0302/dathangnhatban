 @extends('layouts.master')
 @section('title','Danh sách đơn hàng') 

 @section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="front-order-title">Chi Tiết Đơn Hàng</h1>
            @include('notifications.status_message') 
            @include('notifications.errors_message')
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="order-status-bar">
            <ul class="order-route">
                <li class="{{ $order->statusRoute() == 1 ? 'completed' : '' }}">
                    <span class="step-number">01</span>
                    <div class="step-info">
                        <span class="order-status">Kho Trung Quốc</span>        
                    </div>
                </li>
                <li class="{{ $order->statusRoute() == 2 ? 'completed' : '' }}">
                    <span class="step-number">02</span>
                    <div class="step-info">
                        <span class="order-status">Đang vận chuyển</span>
                    </div>
                </li>
                <li class="{{ $order->statusRoute() == 3 ? 'completed' : '' }}">
                    <span class="step-number">03</span>
                    <div class="step-info">
                        <span class="order-status">Kho Đà Nẵng</span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-xs-2">
            <p class="text-right">Tên Khách Hàng:</p>
        </div>
        <div class="col-xs-5">
            <p class="text-left">{{ $order->user->last_name }} {{ $order->user->first_name }}</p>
        </div>
        <div class="col-xs-2">
            <p class="text-right">Điện Thoại:</p>
        </div>
        <div class="col-xs-3">
            <p class="text-left">{{ $order->shipphone }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2">
            <p class="text-right">Ngày Đặt Hàng:</p>
        </div>
        <div class="col-xs-5">
            <p class="text-left">{{ $order->created_at }}</p>
        </div>
        <div class="col-xs-2">
            <p class="text-right">Trạng Thái:</p>
        </div>
        <div class="col-xs-3">
            <p class="text-left">
                @if($order->status===1) <span>Chờ xử lý</span>
                @elseif($order->status===2) <span>Đang xử lý</span> 
                @elseif($order->status===3) <span>Chờ đặt cọc</span> 
                @elseif($order->status===4) <span>Đã đặt cọc</span> 
                @elseif($order->status===5) <span>Khiếu nại</span> 
                @elseif($order->status===6) <span>Đã hoàn thành</span> 
                @elseif($order->status===7) <span>Hủy</span> 
                @else <span>Không xác định!</span> @endif
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2">
            <p class="text-right">Địa Chỉ Nhận Hàng:</p>
        </div>
        <div class="col-xs-5">
            <p class="text-left">{{ $order->shipaddress }}, {{ $order->shipdistrict }}, {{ $order->shipcity }}</p>
        </div>
        <div class="col-xs-5 text-right">
            
            @if($order->status == 1)
            <a type="button" class="btn btn-danger" data-order-id="{{$order->id}}" data-toggle="modal" data-target="#modal-delete-order">
                <i class="fa fa-trash"></i> Hủy đơn hàng
            </a> 
            <!-- <form action="{{ url('order', [$order->id]) }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="DELETE">
                <input type="submit" class="btn btn-danger btn-sm" value="Hủy Đơn Hàng">
            </form> -->
            @endif
        </div>
        <div class="col-xs-2">
            <p class="text-right">Đã Có:</p>
        </div>
        <div class="col-xs-3">
            <?php 
                $groupCount = $orderDetailByShops->map(function ($item, $key) {
                    return collect($item)->count();
                });

                $totalItemType = $groupCount->sum();
            ?>
            <p class="text-left" style="color:red;">{{$available}}/{{$totalItemType}}</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-8 orderdetail-products">
            @foreach($orderDetailByShops as $shop => $orderdetails )
            <div class="row">
                <div class="col-xs-12">
                    <!-- <div class="well well-sm">
                        <span class="shop-group">CỬA HÀNG: {{$orderdetails[0]->shop->name}}</span>
                        <span class="item-color">Tổng Số Lượng: {{$orderdetails->sum('quantity')}}</span>
                    </div> -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x shop-icon-color"></i>
                                <strong class="fa-stack-1x order-shop-index fa-inverse">{{$loop->iteration}}</strong>
                            </span>
                            <span class="shop-group">CỬA HÀNG: {{$orderdetails[0]->shop->name}}</span>
                        </div>
                        <div class="panel-body">
                            <div class="col-xs-6">
                                <p>Tổng Số Lượng: {{$orderdetails->sum('quantity')}}</p>
                                Tổng Giá: 
                                <span class="cny-money" >
                                    <p style="display:inline">{{number_format($orderdetails->sum('total'), 2, ',', '.')}}</p>
                                </span>
                            </div>
                            <div class="col-xs-6">
                                <span>
                                    <p>
                                        Mã Vận Đơn:
                                        @if(!empty($orderdetails[0]->getMaVanDon($orderdetails[0]->shop_id)))
                                            {{$orderdetails[0]->getMaVanDon($orderdetails[0]->shop_id)}}
                                        @else
                                            Chưa có
                                        @endif
                                    </p>
                                </span>
                                <span> 
                                    <p>
                                        Lộ trình :
                                        <span style="color: green">
                                            @if($orderdetails[0]->shopRoute($orderdetails[0]->shop_id) == 1) Kho Trung Quốc
                                            @elseif($orderdetails[0]->shopRoute($orderdetails[0]->shop_id) == 2) Đang vận chuyển
                                            @elseif($orderdetails[0]->shopRoute($orderdetails[0]->shop_id) == 3) Kho Đà Nẵng
                                            @else Không xác định
                                            @endif
                                        </span>
                                    </p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @foreach ($orderdetails as  $item)
                <div class="row">
                    <div class="col-xs-2">
                        <a href="{{$item->url}}" target="_blank">
                            <img src="{{$item->image}}" alt="default" class="img-rounded img-inside">
                        </a>
                    </div>
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-12 text-left item-name">
                                <p title="Tên sản phẩm">{{ $item->productname }}</p>
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
                                <p title="Kích cỡ sản phẩm">{{ $item->size }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2 text-left">
                                <p>Số Lượng:</p>
                            </div>
                            <div class="col-xs-4 text-left item-quantity">
                                <p title="Số lượng sản phẩm" style="{{ $item->quantity==0 ? 'color: red;' : '' }}">
                                    {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p>Màu Sắc:</p>
                            </div>
                            <div class="col-xs-4 item-color">
                                <p title="Màu sắc sản phẩm">{{ $item->color }}</p>
                            </div>
                        </div>
                       <!--  <div class="row">
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label title="Đánh dấu số lượng sản phẩm đã nhận đủ!">
                                        <input class="is-available" data-id="{{$item->id}}" type="checkbox"  
                                               {{ $item->is_available ? 'checked' : '' }}
                                               {{ ($order->status == 6 OR $order->status == 7) ? 'disable' : ''}}>  
                                        Đã nhận
                                    </label>
                                </div>
                            </div>
                        </div> -->
                        <div class="row history">
                            <?php $histories = \App\Models\History::where('orderdetail_id', $item->id)->get(); ?>
                            @if(!empty($histories))
                            <ul>
                            @foreach ($histories as $history)
                                <li>
                                    {!! $history->show($history->created_by, $history->operation, $history-> attribute) !!}
                                    @if($history->operation=='edit')
                                        : {{ $history->old_value }} -> {{ $history->new_value }}
                                    @endif
                                </li>
                            @endforeach
                            </ul>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                @if(!empty($item->note))
                                <span>
                                    <a data-toggle="collapse" href="#collapse{{$item->id}}-note" 
                                       aria-expanded="false" aria-controls="collapse{{$item->id}}-note">
                                        Ghi chú về sản phẩm
                                    </a>
                                </span>
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
                                @if($order->status == 4 OR $order->status == 5 OR $order->status == 6)
                                <span><a data-toggle="collapse" href="#collapse{{$item->id}}FeedBack" aria-expanded="false" aria-controls="collapse{{$item->id}}FeedBack">Khiếu nại về sản phẩm</a></span>
                                @endif
                            </div>
                            <div class="col-xs-12">
                                <div class="collapse" id="collapse{{$item->id}}FeedBack">
                                    <form action="{{ url('/order/item/feedback')}}/{{$item->id}}" method="POST" class="form-horizontal">
                                        {!! method_field('patch') !!} {!! csrf_field() !!}
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea name="feedback" class="form-control" rows="4" placeholder="Nội dung khiếu nại...">{{ $item->feedback }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10 text-right">
                                                <button type="submit" class="btn btn-primary">Gửi Khiếu Nại</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- @if($order->status===1)
                    <div class="col-xs-2 text-left">
                        <form action="{{ url('order/itemdestroy', [$item->id]) }}" method="POST" >
                                {!! csrf_field() !!}
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="submit" class="btn btn-danger btn-sm" value="Xóa">
                        </form>
                    </div>
                    @endif -->
                </div>
                <hr>
                @endforeach
            @endforeach
        </div>
        <div class="col-xs-4">
            <div class="row">
                <div class="col-xs-5">
                    <p>Tỷ giá:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{number_format($order->rate, 2, ',', '.')}}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-5">
                    <p>Tổng giá hàng:</p>
                </div>
                <div class="col-xs-7 text-left cny-money">
                    <p>{{number_format($order->totalamount, 2, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-5">
                    <p>Quy đổi thành:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{ number_format($order->convertTotalAmountToVietNamDong(), 2, ',', '.') }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-5">
                    <p>Phí ship Trung Quốc:</p>
                </div>
                <div class="col-xs-7 text-left cny-money">
                    <p>{{number_format($order->freight1, 2, ',', '.')}}</p>
                </div>
                <div class="col-xs-5">
                    <p>Quy đổi thành:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{ number_format($order->convertFreight1ToVietNamDong(), 2, ',', '.') }}</p>
                </div>
                <div class="col-xs-12">
                    <span><a data-toggle="collapse" href="#collapseFreight1Detail" aria-expanded="false" aria-controls="collapseFreight1Detail">Chi tiết phí ship Trung Quốc</a></span>
                </div>
                <div class="col-xs-12">
                    <div class="collapse" id="collapseFreight1Detail">
                        @if(count($freight1details)<=0)
                            <span>Chưa cập nhật...</span>
                        @else
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
                                    <td>{{$sub->shop->name}}</td>
                                    <td class="cny-money"><p>{{number_format($sub->freight1_sub, 2, ',', '.')}}</p></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-5">
                    <p title="Trọng lượng đơn hàng (KG)">Trọng Lượng:</p>
                </div>
                <div class="col-xs-7">
                    <p>{{number_format($order->weight, 2, ',', '.')}} KG</p>
                </div>
                <div class="col-xs-5">
                    <p title="Giá cước vận chuyển về Đà Nẵng tính theo KG">Cước/KG:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{number_format($order->freight2, 2, ',', '.') }}</p>
                </div>
                <div class="col-xs-5">
                    <p title="Phí vận chuyển từ Trung Quốc về Đà Nẵng">Cước vận chuyển:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{number_format($order->getFreightVN(), 2, ',', '.') }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-5">
                    <p>Phí dịch vụ:</p>
                </div>
                <div class="col-xs-7">
                    <p>{{ $order->service }}%</p>
                </div>
                <div class="col-xs-5">
                    <p>Tiền phí dịch vụ:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{ number_format($order->getServicePrice(), 2, ',', '.') }}</p>
                </div>
                <div class="col-xs-12 item-color">
                    <p>(% giá trị của Tổng giá hàng + Phí Ship TQ)</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-5">
                    <p>Thành tiền:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{ number_format($order->getFinalPrice(), 2, ',', '.') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    <p>Đặt cọc:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money-deposit">
                    <p>{{number_format($order->deposit, 2, ',', '.')}}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-5">
                    <p>Còn lại:</p>
                </div>
                <div class="col-xs-7 text-left vnd-money">
                    <p>{{ number_format($order->getDebtPrice(), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="bs-example bs-order-detail">
                @if($order->status===1)
                <form action="{{ url('/order/note')}}/{{$order->id}}" method="POST" class="form-horizontal">
                    {!! method_field('patch') !!} {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea name="note" class="form-control" rows="4" placeholder="Nội dung ghi chú...">{{ $order->note }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 text-right">
                            <button type="submit" class="btn btn-primary">Ghi Chú</button>
                        </div>
                    </div>
                </form>
                @elseif($order->status !=6 AND $order->status !=7)
                <form action="{{ url('/order/feedback')}}/{{$order->id}}" method="POST" class="form-horizontal">
                    {!! method_field('patch') !!} {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea name="feedback" class="form-control" rows="4" placeholder="Nội dung phản hồi...">{{ $order->feedback }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 text-right">
                            <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                        </div>
                    </div>
                </form>
                @else
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="request" class="form-control" rows="4" placeholder="Ghi chú..." disabled>{{ $order->note }}</textarea>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
 <!-- Modal -->
 <div class="modal modal-danger fade" id="modal-delete-order" style="z-index:2017;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cảnh Báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có muốn hủy đơn hàng này không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Đóng</button>
                <form name="form-order-delete" action="{{ url('order', [$order->id]) }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-danger" value="Hủy Đơn Hàng">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection @section('scripts')
<script>
    $(document).ready(function () {
        //update qty
        $('.is-available').on('click', function() {
            var id = $(this).attr('data-id');
            $.ajax({
                type: "PATCH",
                url: '{{ url("/order/item/ajust/available") }}' + '/' + id,
                data: {
                    // 'is_available':is_available
                },
                success: function(data) {
                    location.reload();
                },
                error: function(textStatus, errorThrown) {
                    location.reload();
                }
            });
        });
    });
</script>
@endsection