<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderCode;
use DB;

class OrderCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order_codes = OrderCode::orderBy('id', 'DESC')->paginate(10);
        return view('admin.order_code.index', compact('order_codes'))
        ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.order_code.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,['order_code'=>'required']);
    
        $order_code = new OrderCode();
        $order_code->order_code = $request->input('order_code');
        $order_code->order_name = $request->input('order_name');
        $order_code->created_by = Auth::user()->id;
        $order_code->save();

        return redirect()->route('admin.order_code.index')
                        ->with('message', 'Thêm mới mã đơn hàng thành công!')
                        ->with('status', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order_code = OrderCode::find($id);
        return view('admin.order_code.edit', compact('order_code'));
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
            'order_code' => 'required'
        ]);

        $order_code = OrderCode::find($id);
        $order_code->order_code = $request->input('order_code');
        $order_code->order_name = $request->input('order_name');
        $order_code->updated_by = Auth::user()->id;
        $order_code->save();

        return redirect()->route('admin.order_code.index')
                        ->with('success', 'Cập nhật mã đơn hàng thành công!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OrderCode::destroy($id);

        return redirect()->back()
                        ->with('message', 'Xóa mã đơn hàng thành công!')
                        ->with('status', 'success');
    }
}
