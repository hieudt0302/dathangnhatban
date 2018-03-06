@extends('layouts.admin')
@section('title', 'Mã Đơn Hàng') 
@section('description', '') 
@section('pageheader', 'Mã đơn hàng') 
@section('pagedescription', 'Danh sách') 
@section('breadarea', 'Mã Đơn Hàng')
@section('breaditem', 'Danh sách') 

@section('content') 

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

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Thêm mã đơn hàng mới</h3>
			</div>
			<div class="box-body">
				{!! Form::open(array('route' => 'admin.order_code.store','method'=>'POST', 'class'=>'form-inline')) !!}
					<div class="form-group">
						<label for="rate">Mã đơn hàng</label> 
						{!! Form::text('order_code', null, array('class' =>'form-control')) !!}
					</div>
					<div class="form-group">
						<label for="rate">Tên đơn hàng</label> 
						{!! Form::text('order_name', null, array('class' =>'form-control')) !!}
					</div>
					<button type="submit" class="btn btn-primary">Thêm mới</button> 
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<i class="fa fa-list-alt"></i>
				<h3 class="box-title">Danh sách đơn hàng</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered">
							<tr>
								<th>#</th>
								<th>Mã đơn hàng</th>
								<th>Tên đơn hàng</th>
								<th>Ngày tạo</th>
								<th>Người tạo</th>
								<th>Tùy chọn</th>
							</tr>
							@foreach ($order_codes as $key => $order_code)
							<tr>
								<td>{{ ++$key }}</td>
								<td>{{ $order_code->order_code }}</td>
								<td>{{ $order_code->order_name }}</td>
								<td>{!! date('d-m-Y', strtotime($order_code->created_at)) !!}</td>
								<td>
									@if(!empty($order_code->created_by)) 
										{{ DB::table('users')->where('id', $order_code->created_by)->first()->username }}
									@endif
								</td>
								<td class="order-option">     
									<a class="btn btn-primary" href="{{ route('admin.order_code.edit',$order_code->id) }}">Sửa</a>                       
                                    <a type="button" class="btn btn-danger" data-id="{{$order_code->id}}" data-toggle="modal" 
                                       data-target="#modal-delete-order_code">
                                    	<i class="fa fa-ban"></i> Xóa
                                    </a> 
                            	</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						{!! $order_codes->render() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-danger fade" id="modal-delete-order_code">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cảnh Báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn chắc chắn muốn xóa mã đơn này ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Đóng</button>
                <form name="form-order_code-delete"  method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-outline" value="Xóa">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#modal-delete-order_code').on('shown.bs.modal', function (e) {
            var id = $(e.relatedTarget).data('id');
            var action = "{{url('admin/order_code')}}/" + id;
            $(e.currentTarget).find('form[name="form-order_code-delete"]').attr("action", action);
        })        
    });
</script>
@endsection