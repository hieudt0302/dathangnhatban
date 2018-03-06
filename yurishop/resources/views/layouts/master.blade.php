<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel="shortcut icon" href="{{url('/')}}/public/assets/favicon.png">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/bootstrap-datepicker.css">
        <!-- MAIN CSS -->
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/master.css">
        <!-- Style item and money -->
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/item.css">
        
        <!-- CUSTOM-->
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/order.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/image.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/table.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/checkbox-radio.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/product-info.css">
        <link rel="stylesheet" href="{{url('/')}}/public/assets/css/spinner.css">
        <script src="{{url('/')}}/public/assets/js/jquery-1.12.4.js"></script>
        <script src="{{url('/')}}/public/assets/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div id="header">
            <?php echo View::make('front.pages.header') ?>
        </div>
        
        <div id="content">
            @yield('content') 
        </div>

        <div id="footer">
            <?php echo View::make('front.pages.footer') ?>
        </div>
        <!-- Support Ajax Request -->
        <script>
            (function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            })();
        </script>
        @yield('scripts')
    </body>       
</html>
