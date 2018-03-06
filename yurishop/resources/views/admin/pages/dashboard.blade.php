@extends('layouts.admin') @section('title', 'Bảng Điều Khiển') @section('description', 'This is a blank description
that needs to be implemented') @section('pageheader', 'Bảng Điều Khiển') @section('pagedescription', 'Tổng Quan') @section('breadarea',
'Bảng Điều Khiển') @section('breaditem', 'Tổng Quan') @section('content')
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
   

@section('content')
			<div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- <h1 class="page-header">
							@if (Auth::guest())
							Admin <small>Dashboard</small>
							@else
							{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}'s <small>Dashboard</small>
							@endif
                            
                        </h1> -->
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Hoạt Động
                            </li>
                        </ol>
						@include('notifications.status_message')
						@include('notifications.errors_message')
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                <div class="col-lg-3 col-xs-6" title="Đơn hàng mới của khách hàng!">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                      <h3>{{$order_new}}</h3>
                      <p>Đơn Đặt Hàng Mới</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ url('/admin/orders') }}" class="small-box-footer">Chi Tiết <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6" title="Đơn hàng đang chờ xử lý">
                  <!-- small box -->
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h3>{{$order_wait}}</h3>
                      <p>Đơn Hàng Đang Xử Lý</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ url('/admin/orders') }}" class="small-box-footer">Chi Tiết <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>
                      {{$lastest_rate}}
                      </h3>
                      <p>Tỷ Giá Hiện Tại</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ url('/admin/rates') }}" class="small-box-footer">Chi Tiết <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6" title="Người dùng mới đăng ký trong tuần này">
                  <!-- small box -->
                  <div class="small-box bg-blue">
                    <div class="inner">
                      <h3>{{$user_numbers}}</h3>
                      <p>Người Dùng Mới</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ url('/admin/users') }}" class="small-box-footer">Chi Tiết <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                
              </div>
                <!-- /.row -->

                <!-- <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Giao Dịch</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Đơn Hàng #</th>
                                                <th>Ngày Đặt</th>
                                                <th>Giờ Đặt</th>
                                                <th>Loại Đơn</th>
                                                <th>Tổng Giá Tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>3326</td>
                                                <td>10/21/2013</td>
                                                <td>3:29 PM</td>
                                                <td>$321.33</td>
                                                <td>$321.33</td>
                                            </tr>
                                          
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
@endsection

@section('scripts')
<!-- Morris.js charts -->
<script src="{{url('/')}}/public/assets/plugins/raphael/raphael.min.js"></script>
<script src="{{url('/')}}/public/assets/plugins/morris.js/morris.min.js"></script>

<script>
    $(document).ready(function () {

  Morris.Donut({
  element: 'sales-chart',
  data: [
    {label: "Download Sales", value: 12},
    {label: "In-Store Sales", value: 30},
    {label: "Mail-Order Sales", value: 20}
  ]
});

});    
</script>
@endsection