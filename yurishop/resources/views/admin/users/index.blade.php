@extends('layouts.admin') @section('title', 'Thành Viên') @section('description', 'This is a blank description that needs
to be implemented') @section('pageheader', 'Thành Viên') @section('pagedescription', 'Danh sách') @section('breadarea', 'Tài
Khoản') @section('breaditem', 'Thành Viên') @section('content') @include('notifications.status_message') @include('notifications.errors_message')



<div class="row">
	<div class="col-xs-12">
		<div class="box">
		<div class="box-header with-border">
                <i class="fa fa-users"></i>
				<!-- <h3 class="box-title"></h3> -->
				<a type="button" class="btn btn-primary pull-right" href="{{ route('admin.users.create') }}">
                                    <i class="fa fa-user-plus"></i> Tạo Mới Tài Khoản
                </a>
        </div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>Tài Khoản</th>
									<th>Họ Tên</th>
									<th>Email</th>
									<th>Trạng Thái</th>
									<th>Quyền</th>
									<th width="280px">Thao Tác</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $key => $user)
								<tr>
									
									<td>{{ ++$i }}</td>
									<td>{{ $user->username }}</td>
									<td>{{ $user->last_name }} {{ $user->first_name }}</td>
									<td>{{ $user->email }}</td>
									<td>
										@if($user->activated===1)
											<label class="label label-success">Đã Kích Hoạt</label> 
										@else
											<label class="label label-warning">Chưa Kích Hoạt</label> 
										@endif
									</td>
									<td>
										@if(!empty($user->roles)) 
											@foreach($user->roles as $v)
											<label class="label label-success">{{ $v->display_name }}</label> 
											@endforeach 
										@endif
									</td>
								
									<td>
										@ability('admin,manager', 'user-show')
										<a class="btn btn-info" href="{{ route('admin.users.show',$user->id) }}">Xem</a> 
										@endability

										@ability('admin,manager', 'user-edit')
										<a class="btn btn-primary" href="{{ route('admin.users.edit',$user->id) }}">Sửa</a> 
										@endability

										@if(Auth::user()->id!=$user->id)
										@role('admin')
										<a type="button" class="btn btn-danger" data-order-id="{{$user->id}}" data-toggle="modal" data-target="#modal-delete-user">
                                    		<i class="fa fa-trash"></i> Xóa
                                    	</a> 
										@endrole
										@endif
									</td>
								
								</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 text-left">
						{!! $data->render() !!}
					</div>
					<div class="col-xs-6"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-danger fade" id="modal-delete-user">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cảnh Báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn xóa người dùng này không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Đóng</button>
                <form name="form-user-delete"  method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-outline" value="Xóa Ngay">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection 

@section('scripts')

<!-- FastClick -->
<script src="{{url('/')}}/public/assets/js/fastclick/fastclick.js"></script>

<script>
	$(document).ready(function () {

		$("#dasboard").removeClass("active");
		$("#order").removeClass("active");
		$("#user").addClass("active");
		$("#setting").removeClass("active");

		$('#modal-delete-user').on('shown.bs.modal', function (e) {
            var userID = $(e.relatedTarget).data('order-id');
            var action = "{{url('admin/users')}}/" + userID;
            $(e.currentTarget).find('form[name="form-user-delete"]').attr("action", action);
        })
	});
	
</script>

@endsection