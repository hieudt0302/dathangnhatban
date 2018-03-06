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
          <div class="form-group">
            <strong>Chi tiết địa chỉ:</strong>
          </div>
          <div class="form-group">
            <p>Tên: {{ $address->first_name }}</p>
          </div>
          <div class="form-group">
            <p>Họ: {{ $address->last_name }}</p>
          </div>
          <div class="form-group">
            <p>Điện Thoại: {{ $address->phone }}</p>
          </div>
          <div class="form-group">
            <p>Fax: {{ $address->fax }}</p>
          </div>
         
          <div class="form-group">
            <p >Địa Chỉ: {{ $address->address }}</p>
          </div>
          <div class="form-group">
            <p>Tỉnh/Thành Phố: {{ $address->city }}</p>
          </div>
          <div class="form-group">
            <p>Mã Bưu Điện (ZipCode): {{ $address->zipcode }}</p>
          </div>
          <div class="form-group">
            <p>Công Ty: {{ $address->company }}</p>
          </div>
          <div class="pull-left">
	            <a class="btn btn-primary" href="{{ route('front.profiles.address') }}"> Trở Lại</a>
	        </div>
        </div>
    </div>
  </div>
  <br>
</div>
@endsection