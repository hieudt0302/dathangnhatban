@extends('layouts.master') 
@section('content')
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
      </div>
      <div class="row">       
        {!! Form::model($address, ['method' => 'PATCH','route' => ['front.profiles.updateaddress', $address->id]]) !!}
        <div class="form-group">
          <label>Tên (*)</label>
          {!! Form::text('first_name', null, array('placeholder' => 'Tên','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
          <label>Họ (*)</label>
           {!! Form::text('last_name', null, array('placeholder' => 'Họ','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
          <label>Điện Thoại (*)</label>
           {!! Form::text('phone', null, array('placeholder' => 'Điện thoại','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
          <label>Fax</label>
          {!! Form::text('fax', null, array('placeholder' => 'Fax','class' => 'form-control')) !!}
        </div>

        <div class="form-group">
          <label>Địa Chỉ (*)</label>
          {!! Form::text('address', null, array('placeholder' => 'Địa chỉ...','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
          <label>Quận/Huyện (*)</label>
          {!! Form::text('district', null, array('placeholder' => 'Tên Quận hoặc Huyện','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
          <label>Tỉnh/Thành Phố (*)</label>
          {!! Form::text('city', null, array('placeholder' => 'Tên Tỉnh hoặc Thành Phố','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
          <label>Mã Bưu Điện (ZipCode)</label>
          {!! Form::text('zipcode', null, array('placeholder' => 'Mã bưu điện','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
          <label>Công Ty</label>
          {!! Form::text('company', null, array('placeholder' => 'Công ty','class' => 'form-control')) !!}
        </div>
        <!-- <div class="form-group"> -->
        <!-- <label for="city">Quận/Huyện</label> -->
        <!-- <select id="city" class="form-control"> -->
        <!-- <option>Hải Châu</option> -->
        <!-- </select> -->
        <!-- </div> -->
        <!-- <div class="form-group"> -->
        <!-- <label for="city">Tỉnh/Thành Phố</label> -->
        <!-- <select id="city" class="form-control"> -->
        <!-- <option>Đà Nẵng</option> -->
        <!-- </select> -->
        <!-- </div> -->
        <!-- <div class="form-group"> -->
        <!-- <label for="city">Quốc Gia</label> -->
        <!-- <select id="city" class="form-control"> -->
        <!-- <option>Việt Nam</option> -->
        <!-- </select> -->
        <!-- </div> -->
        <button type="submit" class="btn btn-default">Lưu</button> 
        {!! Form::close() !!}
      </div>
    </div>
  </div>
  <br>
</div>
@endsection