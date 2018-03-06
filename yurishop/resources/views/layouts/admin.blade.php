<!DOCTYPE html>

<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{url('/')}}/public/assets/favicon.png">
    <!-- Bootstrap -->
    <link href="{{url('/')}}/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{url('/')}}/public/assets/css/datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{url('/')}}/public/assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- Ionicons -->
    <link href="{{url('/')}}/public/assets/css/ionicons/ionicons.min.css" rel="stylesheet">
    <!-- Theme style -->
    <link href="{{url('/')}}/public/assets/css/adminlte/AdminLTE.min.css" rel="stylesheet">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
    <link href="{{url('/')}}/public/assets/css/adminlte/skins/skin-blue.min.css" rel="stylesheet">

      <!-- Morris chart -->
    <link href="{{url('/')}}/public/assets/plugins/morris.js/morris.css" rel="stylesheet">
    
    <!-- Custom  -->
    <link rel="stylesheet" href="{{url('/')}}/public/assets/css/item.css">
    <link rel="stylesheet" href="{{url('/')}}/public/assets/css/spinner.css">
    <link rel="stylesheet" href="{{url('/')}}/public/assets/css/image.css">
    <link rel="stylesheet" href="{{url('/')}}/public/assets/plugins/iCheck/all.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i&amp;subset=vietnamese"
        rel="stylesheet">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<!-- add class sidebar-collapse -->
<body class="hold-transition skin-blue  sidebar-mini">
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">
            <!-- Logo -->
            <a href="{{ url('/admin') }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>ADM</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>ADMIN</b></span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation" >
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" >
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success">4</span>
                                </a>
                                <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="{{url('/')}}/public/assets/img/user-160x160.jpg" class="img-circle" alt="User Image">
                                                </div>
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li> -->
                        <!-- <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">0</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Bạn có 0 thông báo</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 0 thành viên tham gia ngày hôm nay!
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li> -->
                        <!-- <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Bạn còn 0 việc cần làm</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{url('/')}}/public/assets/img/user-160x160.jpg" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ Auth::user()->last_name }} {{ Auth::user()->first_name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="{{url('/')}}/public/assets/img/user-160x160.jpg" class="img-circle" alt="User Image">

                                    <p>
                                        {{ Auth::user()->last_name }} {{ Auth::user()->first_name }} - {{ Auth::user()->roles->first()->name }}
                                        <small>Thành viên từ {{ Auth::user()->created_at }}</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <!-- <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </div> -->
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <!-- <a href="{{ url('/') }}/admin/users/{{Auth::user()->id}}" class="btn btn-default btn-flat">Thông Tin</a> -->
                                        <a href="{{ url('/') }}/admin/users/{{Auth::user()->id}}">
                                            <i class="fa fa-fw fa-info"></i> Thông Tin
                                        </a>
                                    </div>
                                    <div class="pull-right">
                                        <!-- <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Thoát</a> -->
                                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa fa-fw fa-power-off"></i> Thoát
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li> -->
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{url('/')}}/public/assets/img/user-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ Auth::user()->last_name }} {{ Auth::user()->first_name }}</p>
                        <!-- Status -->
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <!-- search form (Optional) -->
                <!-- <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form> -->
                <!-- /.search form -->

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">QUẢN LÝ HỆ THỐNG</li>
                    <!-- Optionally, you can add icons to the links -->
                    <!-- <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
                    <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li> -->
                    <li id="dasboard" class="active treeview">
                        <a href="{{ url('/admin') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Bảng Điều Khiển</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="active"><a href="{{ url('/admin') }}"><i class="fa fa-circle-o"></i> Dashboard</a></li>
                        </ul>
                    </li>
                    @ability('admin,manager', 'order-list')
                    <li id="order" class="treeview">
                        <a href="#"><i class="fa fa-shopping-cart"></i> <span>Đơn Đặt Hàng</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="{{ url('/admin/orders') }}" ><i class="fa fa-circle-o"></i> Danh Sách 
                                    <span class="pull-right-container">
                                        @if(Order::countnew() > 0)
                                        <small class="label pull-right bg-green-active" title="{{Order::countnew()}} đơn hàng đang cần duyệt">{{Order::countnew()}} Mới</small>
                                        @endif
                                    </span>
                                </a>
                            </li>
                            <li><a href="{{ route('admin.orders.route') }}"><i class="fa fa-circle-o"></i> Lộ trình đơn hàng</a></li>
                            <!-- <li>
                                <a href="{{ url('/admin/ordershops') }}">
                                    <i class="fa fa-circle-o"></i> Cửa Hàng
                                    <span class="pull-right-container">
                                        @if(OrderShop::countwork() > 0)
                                        <small class="label pull-right bg-orange" title="{{OrderShop::countwork()}} đơn hàng đang làm việc">{{OrderShop::countwork()}}</small>
                                        @endif
                                        @if(OrderShop::countnew() > 0)
                                        <small class="label pull-right bg-green-active" title="{{OrderShop::countnew()}} đơn hàng đang cần gửi">{{OrderShop::countnew()}} Mới</small>
                                        @endif
                                    </span>
                                </a>
                            </li> -->
                        </ul>
                    </li>
                    @endability
                    @ability('admin,manager', 'user-list')
                    <li id="user" class="treeview">
                        <a href="#"><i class="fa fa-users"></i> <span>Tài Khoản</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/users') }}"><i class="fa fa-circle-o"></i> Thành Viên</a></li>
                            <!-- @permission('user-list')
                            <li><a href="{{ url('/admin/roles') }}"><i class="fa fa-circle-o"></i> Quyền</a></li>
                            @endpermission -->
                        </ul>
                    </li>
                    @endability
                    <li id="setting" class="treeview">
                        <a href="#"><i class="fa fa-wrench"></i> <span>Cài Đặt</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/rates') }}"><i class="fa fa-circle-o"></i> Tỷ Giá</a></li>
                            <li><a href=""><i class="fa fa-circle-o"></i> Thiết Lập Hệ Thống</a></li>
                        </ul>
                    </li>
                    <li class="header">XEM NHANH</li>
                    <li><a href="{{ url('/admin/orders') }}" target="_blank"><i class="fa fa-circle-o text-red"></i> <span>Đơn Đặt Hàng</span></a></li>
                    <!-- <li><a href="{{ url('/admin/ordershops') }}" target="_blank"><i class="fa fa-circle-o text-yellow"></i> <span>Cửa Hàng</span></a></li> -->
                    <li><a href="{{ url('/admin/rates') }}" target="_blank"><i class="fa fa-circle-o text-aqua"></i> <span>Tỷ Giá</span></a></li>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>@yield('pageheader')<small>@yield('pagedescription')</small></h1>
                <ol class="breadcrumb">
                    <li><a href="javascript:;"><i class="fa fa-dashboard"></i> Quản Lý</a></li>
                    <li><a href="javascript:;">@yield('breadarea')</a></li>
                    <li class="active">@yield('breaditem')</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content container-fluid">
                <!--------------------------| Your Page Content Here | -------------------------->
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <!-- <div class="pull-right hidden-xs">
                Designed by <a href="http://jcs-corp.com" target="_blank">Japan Computer Software Co., Ltd</a>
            </div> -->
            <!-- Default to the left -->
            <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="https://www.taobaodanang.com/" target="_blank">TAOBAO DA NANG</a>.</strong>            All rights reserved
        </footer>

        <!-- Control Sidebar -->

        <!-- bravohex: REMOVE -->

        <!-- /.control-sidebar -->

        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery-->
    <script src="{{url('/')}}/public/assets/js/jquery-1.12.4.js"></script>

    <!-- Bootstrap -->
    <script src="{{url('/')}}/public/assets/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    <script src="{{url('/')}}/public/assets/js/adminlte/adminlte.min.js"></script>

    <!-- iCheck -->
    <script src="{{url('/')}}/public/assets/plugins/iCheck/icheck.min.js"></script>


    <!-- Support Ajax Request -->
    <script>
    (function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })();
    </script><!-- Support Ajax Request -->
    
    @yield('scripts')
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>
