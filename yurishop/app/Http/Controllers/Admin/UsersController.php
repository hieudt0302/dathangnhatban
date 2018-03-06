<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use DB;
use Hash;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('admin')) {
            $data = User::orderby('id', 'desc')->where('deleted', 0)->paginate(20);
        } else {
            $admin_user_ids = DB::table('roles as r')
            ->join('role_user as ru', 'ru.role_id', 'r.id')
            ->where('r.name', 'admin')
            ->pluck('ru.user_id')
            ->toArray();
            
            $data = User::orderby('id', 'desc')
                ->whereNotIn('id', $admin_user_ids )
                ->where('deleted', 0)
                ->paginate(20);
        }


        return view('admin.users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
              
        if (!empty($user->roles) && $user->hasRole('admin')) {
            $roles = Role::pluck('display_name', 'id');
        } elseif (!empty($user->roles) && $user->hasRole('manager')) {
            $roles = Role::where('name', '!=', 'admin')->orWhereNull('name')->pluck('display_name', 'id');
        } elseif (!empty($user->roles) && $user->hasRole('normal')) {
            $roles = Role::whereNotIn('name', ['admin','manager'])->orWhereNull('name')->pluck('display_name', 'id');
        } else {
            return abort(404);
        }

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users,username|min:6',  
            'first_name'=>'required',
            'last_name'=>'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|phone:AUTO,VN',
            'password' => 'required|same:confirm_password',
            'roles'=>'required',
        ],
        [
            'username.required'    => 'Yêu cầu nhập tên tài khoản.',
            'username.unique'    => 'Tên tài khoản đã tồn tại.',
            'username.min'    => 'Tên tài khoản phải ít nhất 6 ký tự',
            'first_name.required'    => 'Yêu cầu nhập Tên người dùng.',
            'last_name.required'    => 'Yêu cầu nhập Họ người dùng.',
            'phone.phone'    => 'Số điện thoại không đúng.',
            'phone.required'    => 'Yêu cầu nhập số điện thoại.',
            'password.same'    => 'Mật khẩu xác nhận không khớp.',
            'password.required'    => 'Yêu cầu nhập mật khẩu',
            'roles.required'    => 'Cần phải thiết lập tối thiểu một quyền cho tài khoản',
        ]);
        
        $input =  $request->except(['confirm_password','roles']);
        $input['password'] = Hash::make($input['password']);
        $input['activated'] = $request->input('activated');

        $user = User::create($input);

        if (!empty($request->input('roles'))) {
            foreach ($request->input('roles') as $key => $value) {
                $user->attachRole($value);
            }
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (empty($user) || $user->deleted===1) {
            return redirect()->back()
            ->with('message', 'Người dùng không tồn tại!')
            ->with('status', 'danger');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

      
        if (Auth::user()->hasRole('admin')) {
            $roles = Role::pluck('display_name', 'id');
        } elseif (!empty($user->roles) && Auth::user()->hasRole('manager')) {
            $roles = Role::where('name', '!=', 'admin')->orWhereNull('name')->pluck('display_name', 'id');
        } elseif (!empty($user->roles) && Auth::user()->hasRole('normal')) {
            $roles = Role::whereNotIn('name', ['admin','manager'])->orWhereNull('name')->pluck('display_name', 'id');
        } else {
            return abort(404);
        }

        $userRole = $user->roles->pluck('id', 'id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username' => 'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|phone:AUTO,VN',
            'password' => 'same:confirm_password',
        ],
        [
            'first_name.required'    => 'Yêu cầu nhập Tên người dùng.',
            'last_name.required'    => 'Yêu cầu nhập Họ người dùng.',
            'phone.phone'    => 'Số điện thoại không đúng.',
            'phone.required'    => 'Yêu cầu nhập số điện thoại.',
            'password.same'    => 'Mật khẩu xác nhận không khớp.',
        ]);

        $input =  $request->except(['username','email' ,'confirm_password','roles']);
      
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));
        }
        

        $user = User::find($id);
        $user->update($input);

     
        
        DB::table('role_user')->where('user_id', $id)->delete();

        if (!empty($request->input('roles'))) {
            foreach ($request->input('roles') as $key => $value) {
                $user->attachRole($value);
            }
        }
        

        return redirect()->back()
                        ->with('message', 'Cập nhật thông tin người dùng thành công!')
                        ->with('status', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user =  User::find($id);
        if (Auth::user()->id == $user->id) {
            return abort(404);
        }

        $user->delete();
                
        return redirect()->route('admin.users.index')
        ->with('message', 'Bạn vừa xóa thành công tài khoản [' . $user->username . '] của người dùng [' . $user->last_name .' '. $user->first_name.']')
        ->with('status', 'success');
    }
}
