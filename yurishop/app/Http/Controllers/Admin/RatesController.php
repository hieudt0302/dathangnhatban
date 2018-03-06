<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Rate;
use Validator;
use App\Models\User;
use DB;

class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rates = Rate::orderBy('id', 'DESC')->paginate(10);
        return view('admin.rates.index', compact('rates'))
        ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.rates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric|min:0|max:999999.99'
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->with('message', 'Bạn nhập tỷ giá không phù hợp!')
                ->with('status', 'danger');
        }
    
        $rate = new Rate();
        $rate->rate = $request->input('rate');
        $rate->user_id = Auth::user()->id;
        $rate->save();

        return redirect()->route('admin.rates.index')
                        ->with('message', 'Thêm mới tỷ giá thành công!')
                        ->with('status', 'success');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rate = Rate::find($id);
        return view('admin.rates.show', compact('rate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rate = Rate::find($id);
        return view('admin.rates.edit', compact('rate'));
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
            'rate' => 'required'
        ]);

        $rate = Rate::find($id);
        $rate->rate = $request->input('rate');
        $rate->user_id = Auth::user()->id;
        $role->save();

        return redirect()->route('admin.rates.index')
                        ->with('success', 'Cập nhật tỷ giá thành công!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rate =  Rate::find($id);
        
        $role->delete();

        return redirect()->back()
                        ->with('message', 'Xóa tỷ giá thành công!')
                        ->with('status', 'success');
    }
}
