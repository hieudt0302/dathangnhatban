 @extends('layouts.master') @section('title','Danh sách đơn hàng') 
 @section('content')
<style>
#toDate, #fromDate{
    width: 120px;
}
</style>
<div class="container">
    @include('notifications.status_message') 
    @include('notifications.errors_message')

    <div class="row">
       <div class="col-xs-6">
            <h2 class="front-order-title">Danh Sách Đơn Hàng</h2>
       </div>
       <div class="col-xs-6">
           <a href="{{url('/')}}" class="btn btn-primary" style="margin-top: 20px; float: right;">
                <i class="fa fa-shopping-basket"></i> Thêm sản phẩm
            </a>
       </div>
    </div>
  
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-table">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                                {!! Form::open(array('route' => 'front.orders.find','method'=>'POST', 'class'=>'form-inline')) !!}
                                <div class="form-group">
                                    <label for="landingCode" class="front-order-label">Mã Vận Đơn</label> 
                                    {!! Form::text('landingCode', null, array('class' => 'form-control', 'id'=>'landingCode')) !!}
                                </div>
                                <span style="margin-left:20px"></span>
                                <div class="form-group">
                                    <label for="fromDate" class="front-order-label">Từ Ngày</label> 
                                    {!! Form::text('fromDate', null, array('class' => 'form-control', 'id'=>'fromDate')) !!}
                                </div>
                                <span style="margin-left:20px"></span>
                                <div class="form-group">
                                    <label for="toDate" class="front-order-label">Đến Ngày</label> 
                                    {!! Form::text('toDate', null, array('class' => 'form-control', 'id'=>'toDate')) !!}
                                </div>
                                <span style="margin-left:20px"></span>
                                <div class="form-group">
                                    <label for="status" class="front-order-label">Trạng Thái</label> 
                                    {!! Form::select('status', array(0=>'Tất Cả', 1 => 'Chờ xử lý', 2 => 'Đang xử lý', 
                                                                     3 => 'Chờ đặt cọc',  4 => 'Đã đặt cọc', 5 => 'Khiếu nại', 
                                                                     6 => 'Đã hoàn thành', 7 => 'Hủy'), 
                                                     0, array('name' => 'status','type'=>'text', 'class'=>'form-control')) !!}
                                </div>
                                <span style="margin-left:20px"></span>
                                <button type="submit" class="btn btn-primary">Tìm Kiếm</button> {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-list">
                        <thead>
                            <tr>
                                <th class="front-order-label">#</th>
                                <th class="front-order-label">Thời gian đặt hàng</th>
                                <th class="front-order-label">Tổng giá trị đơn hàng</th>
                                <th class="front-order-label">Tình trạng</th>
                                <th class="front-order-label"><em class="fa fa-cog"></em></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($orders))
                                <tr>
                                    <td>
                                        <h3>Không có đơn hàng nào</h3>
                                    </td>
                                </tr>
                            @else
                                @foreach ($orders as $key => $order)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td class="vnd-money"><p>{{ number_format($order->getFinalPrice(), 2, ',', '.') }}</p></td>
                                    <td>
                                        @if($order->status===1)
                                        <span class="label label-primary">Chờ xử lý</span>
                                        @elseif($order->status===2)
                                        <span class="label label-primary">Đang xử lý</span>
                                        @elseif($order->status===3)
                                        <span class="label label-primary">Chờ đặt cọc</span>
                                        @elseif($order->status===4)
                                        <span class="label label-success">Đã đặt cọc</span>
                                        @elseif($order->status===5)
                                        <span class="label label-warning">Khiếu nại</span>
                                        @elseif($order->status===6)
                                        <span class="label label-success">Đã hoàn thành</span>
                                        @elseif($order->status===7)
                                        <span class="label label-danger">Hủy</span>
                                        @else
                                        <span>Không xác định!</span> 
                                        @endif
                                    </td>
                                    <td class="order-option">

                                        <a href="{{ route('front.orders.show', $order->id) }}" class="btn btn-info" title="Xem chi tiết">
                                            <em class="fa fa-list"></em>
                                        </a>
                                        <!-- @if($order->status===1)
                                        
                                        <a class="btn btn-danger" data-toggle="ajaxModal"><em class="fa fa-trash"></em></a>
                                        @endif -->
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-xs-12">
                            @if(!empty($landingCode) || !empty($fromDate) || !empty($toDate) || !empty($status)) {!! $orders->appends(['from' => $fromDate ])->appends(['to'
                            => $toDate ])->appends(['status' => $status])->render() !!} @else {!! $orders->render() !!} @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{url('/')}}/public/assets/js/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function () {
        $('#fromDate, #toDate').datepicker({
            autoclose: true,
            todayHighlight: true,
            orientation: 'bottom',
            format: 'yyyy-mm-dd',
        });
    });
</script>
@endsection