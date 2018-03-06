@extends('layouts.admin') @section('title', 'Đơn Hàng Cửa Hàng') @section('description', 'This is a blank description that
needs to be implemented') @section('pageheader', 'Đặt Hàng Theo Cửa Hàng') @section('pagedescription', 'Danh sách') @section('breadarea',
'Đơn Đặt Hàng') @section('breaditem', 'Cửa Hàng') @section('content') @include('notifications.status_message') @include('notifications.errors_message')
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
        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="fa fa-shopping-bag"></i>
                <h3 class="box-title">Bộ Lọc</h3>
            </div>
            <div class="box-body">
                {!! Form::open(array('route' => 'admin.ordershops.find','method'=>'POST', 'class'=>'')) !!}
                <div class="row">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="shopname">Tên Cửa Hàng</label> {!! Form::text('shopname', null, array('class' => 'form-control',
                            'id'=>'shopname')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="fromDate">Mã Vận Đơn</label> {!! Form::text('landingcode', null, array('class' =>
                            'form-control', 'id'=>'landingcode')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="status">Trạng Thái</label> {!! Form::select('status', array(0=>'Tất Cả', 1 => 'Chờ
                            xử lý', 2 => 'Đang xử lý', 3 => 'Hoàn Thành', 4 => 'Hủy'), 0, array('name' => 'status','type'=>'text',
                            'class'=>'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="status">Tìm Kiếm</label>
                            <button type="submit" class="btn btn-info form-control">
                                <span class="fa fa-search"></span> 
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="fromDate">Từ Ngày</label> {!! Form::text('fromDate', null, array('class' => 'form-control',
                            'id'=>'fromDate')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label for="toDate">Đến Ngày</label> {!! Form::text('toDate', null, array('class' => 'form-control',
                            'id'=>'toDate')) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Đơn Hàng</h3>
                <!-- <a type="button" class="btn btn-primary pull-right" href="{{URL::to('admin/ordershops/export/1/xlsx') }}">
                                    <i class="fa fa-print"></i> Export
                        </a> -->
            </div>
            <div class="box-body">
            <div class="row">
                    <div class="col-xs-12">
                    <table id="ordershop-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Cửa Hàng</th>
                                    <th>Mã Vận Đơn</th>
                                    <th>Tổng Tiền</th>
                                    <th>Ngày Tạo</th>
                                    <th>Trạng Thái</th>
                                    <th>Tùy chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ordershops as $key => $ordershop)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        {{ $ordershop->name }}
                                    </td>
                                    <td>
                                        {{ $ordershop->landingcode }}
                                    </td>
                                    <td class="cny-money">
                                        <p>{{number_format($ordershop->totalamount + $ordershop->freight1 + $ordershop->freight2, 2, ',', '.')}}</p>
                                    </td>
                                    <td>
                                        {{ $ordershop->created_at }}
                                    </td>
                                    <td>
                                        @if($ordershop->status===1)
                                        <span class="label label-primary">Chờ xử lý</span> @elseif($ordershop->status===2)
                                        <span class="label label-warning">Đang làm việc</span> @elseif($ordershop->status===3)
                                        <span class="label label-success">Đã giao hàng</span> @elseif($ordershop->status===4)
                                        <span class="label label-danger">Hủy</span> @else
                                        <span>Không xác định!</span> @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.ordershops.show', $ordershop->id) }}" class="btn btn-info" title="Xem chi tiết"><i class="fa fa-eye"></i> Xem</a>                                        @if($ordershop->status===1)
                                        <a type="button" class="btn btn-warning ajust-status" data-id="{{$ordershop->id}}"><i class="fa fa-ban"></i> Hủy</a>                                        @endif
                                        <a type="button" class="btn btn-danger" data-ordershop-id="{{$ordershop->id}}" data-toggle="modal" data-target="#modal-permanently-delete-ordershop"><i class="fa fa-trash-o"></i> Xóa</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            <div class="row">
                
                <div class="col-xs-6 text-left">
                        @if(!empty($shopname) || !empty($landingcode) || !empty($status || !empty($fromDate) || !empty($toDate))) 
                        {!! $ordershops->appends(['shopname' => $shopname ])->appends(['landingcode'=>
                        $landingcode ])->appends(['status' => $status])->appends(['fromDate' => $fromDate])->appends(['toDate' => $toDate])->render() !!} @else {!! $ordershops->render() !!}
                        @endif
                    </div>
                    <!-- <div class="col-xs-6 text-right">
                        <a type="button" class="btn btn-primary" href="{{URL::to('admin/ordershops/export/xlsx') }}">
                            <i class="fa fa-print"></i> Export
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>            





<div class="modal modal-danger fade" id="modal-permanently-delete-ordershop">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cảnh Báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có thật sự muốn xóa đơn đặt hàng này không? Không thể khôi phục sau khi đã xóa dữ liệu.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Không</button>
                <form name="form-ordershop-permanently-delete" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-outline" value="Xóa Đơn Hàng">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection @section('scripts')


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

        $('#modal-permanently-delete-ordershop').on('shown.bs.modal', function (e) {
            var orderID = $(e.relatedTarget).data('ordershop-id');
            var action = "{{url('admin/ordershops')}}/" + orderID;
            $(e.currentTarget).find('form[name="form-ordershop-permanently-delete"]').attr("action",
                action);
        })

        $('.ajust-status').on('click', function () {
            var id = $(this).attr('data-id');
            $.ajax({
                type: "PATCH",
                url: '{{ url("/admin/ordershops/ajust/status") }}' + '/' + id,
                data: {
                    'is_available': 0
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