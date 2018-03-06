<?php

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\Guard;
use App\Models\User;
use App\Models\BookAddress;
use App\Models\Role;
use DB;
use Hash;

class ProfileController extends Controller
{
     use AuthenticatesUsers;
    /**
     * Auth guard
     *
     * @var
     */
    protected $auth;

    public function __construct(Guard $auth)
    {
		$this->auth = $auth;
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
         $user = Auth::user();
         if($user->hasRole(['manager', 'admin']))
         {
            return redirect()->route('admin.dashboard');
         }
         return view('front.profiles.index', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
          $this->validate($request, [
            'username' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|phone:AUTO,VN',
            'password' => 'same:confirm_password',
            'current_password' =>'required',
        ]);

        $input =  $request->except(['confirm_password','roles','username','current_password']);
      
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }

        //check current passoword
        $username   = $request->get('username');
        $password   = $request->get('current_password');
        // if(!empty($request->get('password')))
        // {
            if (!$this->auth->attempt([
            'username'     => $username,
            'password'  => $password,
			'activated'  => 1,
            ], false)){
                return redirect()->back()
                ->with('danger','Mật khẩu hiện tại không chính xác')
                ->withInput();
            }
        //}
        
        
        //end check
        $user->update($input);

        return redirect()->route('front.profiles.index')
                        ->with('success','Cập nhật thông tin tài khoản thành công!');
    }

    /* BOOK ADDRESS */
    /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function address(Request $request)
    {
         $address = BookAddress::orderBy('id','DESC')->where('user_id',Auth::user()->id)->paginate(10);
        return view('front.profiles.address',compact('address'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createaddress()
    {
        return view('front.profiles.createaddress');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeaddress(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
			'phone' => 'required',
            'city' => 'required',
            'district' => 'required'
        ]);

        $address = new BookAddress();
        $address->first_name = $request->input('first_name');
        $address->last_name = $request->input('last_name');
        $address->phone = $request->input('phone');
		$address->fax = $request->input('fax');
		$address->address = $request->input('address');
		$address->city = $request->input('city');
        $address->district = $request->input('district');
		$address->zipcode = $request->input('zipcode');
		$address->company = $request->input('company');
        $address->user_id = Auth::user()->id;
        $address->save();

        return redirect()->route('front.profiles.address')
                        ->with('success','Thêm địa chỉ mới thành công!');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showaddress($id)
    {
        $address = BookAddress::find($id);
        return view('front.profiles.showaddress',compact('address'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editaddress($id)
    {
        $address = BookAddress::find($id);
        return view('front.profiles.editaddress',compact('address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateaddress(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
			'phone' => 'required',
            'city' => 'required',
            'district' => 'required',
        ]);

        $address = BookAddress::find($id);
        $address->first_name = $request->input('first_name');
        $address->last_name = $request->input('last_name');
        $address->phone = $request->input('phone');
		$address->fax = $request->input('fax');
		$address->address = $request->input('address');
        $address->district = $request->input('district');
		$address->city = $request->input('city');
		$address->zipcode = $request->input('zipcode');
		$address->company = $request->input('company');
        $address->user_id = Auth::user()->id;
        $address->save();


        return redirect()->route('front.profiles.address')
                        ->with('success','Cập nhật địa chỉ thành công!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyaddress($id)
    {
      // DB::table("roles")->where('id',$id)->delete();
        $address =  BookAddress::find($id);
     
        $address->delete();

        return redirect()->route('front.profiles.address')
                        ->with('success','Xóa địa chỉ thành công!');
    }
}
