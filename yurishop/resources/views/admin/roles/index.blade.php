@extends('layouts.admin')

@section('content')
   <div class="row">
	   <div class="col-lg-12 margin-tb">
		   <div class="pull-left">
			   <h2>Role Management</h2>
		   </div>
		   <div class="pull-right">
			   @permission('role-create')
			   <a class="btn btn-success" href="{{ route('admin.roles.create') }}"> Create New Role</a>
			   @endpermission
		   </div>
	   </div>
   </div>
   @if ($message = Session::get('success'))
	   <div class="alert alert-success">
		   <p>{{ $message }}</p>
	   </div>
   @endif
   <table class="table table-bordered">
	   <tr>
		   <th>No</th>
		   <th>Name</th>
		   <th>Description</th>
		   <th width="280px">Action</th>
	   </tr>
   @foreach ($roles as $key => $role)
   <tr>
	   @if($role->name==='admin')
		   @role('admin')
		   <td>{{ ++$i }}</td>
		   <td>{{ $role->display_name }}</td>
		   <td>{{ $role->description }}</td>
		   <td>
			   <a class="btn btn-info" href="{{ route('admin.roles.show',$role->id) }}">Show</a>
		   </td>
		   @endrole
	   @else 
		   <td>{{ ++$i }}</td>
		   <td>{{ $role->display_name }}</td>
		   <td>{{ $role->description }}</td>
		   <td>
			   @permission('role-show')
			   <a class="btn btn-info" href="{{ route('admin.roles.show',$role->id) }}">Show</a>
			   @endpermission
			   @role('admin')
			   <a class="btn btn-primary" href="{{ route('admin.roles.edit',$role->id) }}">Edit</a>
			   {!! Form::open(['method' => 'DELETE','route' => ['admin.roles.destroy', $role->id],'style'=>'display:inline']) !!}
			   {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
			   {!! Form::close() !!}
			   @endrole
		   </td>
	   @endif
   </tr>
   @endforeach
   </table>
   {!! $roles->render() !!}
@endsection