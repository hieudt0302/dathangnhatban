@extends('layouts.admin') 
@section('title', 'Thành Viên') 
@section('description', 'This is a blank description that needs
to be implemented') 
@section('pageheader', 'Thành Viên') 
@section('pagedescription', 'Cập Nhật') 
@section('breadarea', 'Tài Khoản') 
@section('breaditem', 'Thành Viên') 

@section('content') 
@include('notifications.status_message') 
@include('notifications.errors_message')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-users"></i>
                <h3 class="box-title">Cập Nhật Tài Khoản</h3>
                <a type="button" class="btn btn-primary pull-right" href="{{ route('admin.users.index') }}">
                        <i class="fa fa-chevron-left"></i> Trở Lại
                </a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        {!! Form::model($user, ['method' => 'PATCH','route' => ['admin.users.update', $user->id]]) !!}
                        <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tài Khoản:</strong> {!! Form::text('username', null, array('readonly', 'placeholder'
                                    => '','class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tên:</strong> {!! Form::text('first_name', null, array('placeholder' =>
                                    '','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Họ:</strong> {!! Form::text('last_name', null, array('placeholder' =>
                                    '','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Điện Thoại:</strong> {!! Form::text('phone', null, array('placeholder' => '','class'
                                    => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Email:</strong> {!! Form::text('email', null, array( 'readonly','placeholder'
                                    => '','class' => 'form-control')) !!}
                                </div>
                            </div>



                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Mật Khẩu:</strong> {!! Form::password('password', array('placeholder' => '','class'
                                    => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Xác Nhận Mật Khẩu:</strong> {!! Form::password('confirm_password', array('placeholder'
                                    => '','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Quyền:</strong> {!! Form::select('roles[]', $roles,$userRole, array('class' =>
                                    'form-control','multiple')) !!}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="activated" value="0">
                                    <label>{{ Form::checkbox('activated', 1 , $user->activated ? true : false, array('class' => 'name')) }}
                                            Kích Hoạt</label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn-lg btn-primary">Cập Nhật</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection