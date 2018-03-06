<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AppSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //NOT IMPLEMENTION
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //NOT IMPLEMENTION
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //NOT IMPLEMENTION
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $appsetting = DB::table('appsettings')->first();
        
        if (empty($appsetting) ) {
            return redirect()->back()
            ->with('message', 'Cài đặt chưa được khởi tạo, vui lòng liên hệ quản trị hệ thống')
            ->with('status', 'danger');
        }
        return view('admin.appsettings.show', compact('appsetting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appsetting = DB::table('appsettings')->where('id',$id)->first();
        return view('admin.appsettings.edit', compact('appsetting'));
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
            'freight_to_vn' => 'required|numeric|min:0'
        ]);

        $appsetting = DB::table('appsettings')->where('id',$id)->update(
            array('freight_to_vn'    =>  $request->input('freight_to_vn')));

        return redirect()->route('admin.appsettings.show')
                        ->with('success', 'Thiết lập thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //NOT IMPLEMENTION
    }
}
