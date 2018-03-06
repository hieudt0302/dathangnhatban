@extends('layouts.master') @section('content')
<div class="container">
     <br>
    <div class="row">
        <div class="col-md-3">
            <?php echo View::make('front.pages.profilesidebar') ?>
        </div>
        <div class="col-md-9">           
            <div class="row">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Có một vài vấn đề với dữ liệu của bạn.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif 
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @elseif ($message = Session::get('danger'))
                <div class="alert alert-danger">
                    <p>{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="row">
                {!! Form::model($user, ['method' => 'PATCH','route' => ['front.profiles.update']]) !!}
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Tài Khoản</label> {!! Form::text('username', null, array('readonly', 'placeholder' => 'User
                        Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Tên (*)</label> {!! Form::text('first_name', null, array('placeholder' => 'First Name','class'
                        => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Họ (*)</label> {!! Form::text('last_name', null, array('placeholder' => 'Last Name','class'
                        => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Điện Thoại (*)</label> {!! Form::text('phone', null, array('placeholder' => 'Phone','class'
                        => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Email (*)</label> {!! Form::text('email', null, array( 'placeholder' => 'Email','class' =>
                        'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Mật Khẩu Hiện Tại</label> {!! Form::password('current_password', array('placeholder' => 'Password','class'
                        => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Mật Khẩu</label> {!! Form::password('password', array('placeholder' => 'Password','class'
                        => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Xác Nhận Lại Mật Khẩu</label> {!! Form::password('confirm_password', array('placeholder'
                        => 'Confirm Password','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
<br>
</div>
@endsection