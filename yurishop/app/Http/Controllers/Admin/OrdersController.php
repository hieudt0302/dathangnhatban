<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\OrderShop;
use App\Models\Rate;
use App\Models\Shop;
use App\Models\Freight1Detail;
use App\Models\History;
use Validator;
use DB;
use Excel;
use Carbon\Carbon;
use App\Notifications\OrderProcessNotification;
use App\Notifications\OrderModifiedNotification;
use App\Http\Controllers\Front\PagesController;

class OrdersController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->filter($request);
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::where('id', $id)->first();

        $rates = Rate::orderBy('created_at', 'DESC')->first();

        if (empty($rates)) {
            $rate =0;
            $final = floatval(Cart::total(2, '.', '')) *  $rate;
            return view('front.orders.create', compact('bookaddress', 'rate', 'final'));
        }
        $rate = $rates->rate;

        $orderdetails = OrderDetail::where('order_id', $order->id)->where('is_deleted', 0)->get();

        // $available  = $orderdetails->where('is_available', true)->count();

        $freight1details = Freight1Detail::where('order_id',$id)->get();

        return view('admin.orders.show', compact('order', 'rate', 'orderdetails','freight1details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    public function save(Request $request, $id)
    {
        $order = Order::find($id);
        
        if (empty($order)) {
            return redirect()->back()
            ->with('message', 'Không tìm thấy đơn đặt hàng theo mã: '. $id)
            ->with('status', 'danger');
        }

        $status = $request->status != null ? $request->status : $order->status;
        $freight1 = $request->freight1;
        $freight2 = $request->freight2;
        $weight = $request->weight;
        $service = $request->service;
        $deposit = $request->deposit;

        $old_status = $order->status;

        if($old_status != $status) { 
            $order->status= $status;
            if($status == 4){
                $order->is_deposited = 1;
            }
            if($order->is_deposited != 1){ // nếu đơn hàng chưa được đặt cọc thì có thể đổi thời gian đặt hàng
                $order->created_at = Carbon::now();  
            }
            if($order->usercreated == null){ // nếu là người đầu tiên thay đổi trạng thái đơn hàng
                $order->usercreated = Auth::user()->id;  // thì trở thành người duyệt đơn
            }         
        }

        if($order->freight1 != $freight1 || $order->freight2 != $freight2 || $order->weight != $weight || $order->service != $service || $order->deposit != $deposit){
            $order->userupdated = Auth::user()->id;
        }

        if (!empty($freight1)) {
            $order->freight1= $freight1;
        }
        if (!empty($freight2)) {
            $order->freight2= $freight2;
        }
        if (!empty($weight)) {
            $order->weight= $weight;
        }
        if (!empty($service)) {
            $order->service= $service;
        }
        if (!empty($deposit)) {
            $order->deposit= $deposit;
        }
        
        $order->save();

        OrderDetail::where('order_id', $id)->update(['usercreated' => Auth::user()->id]);
        
        if ($old_status != $order->status && ($status==3 || $status==4 || $status==7)) {      
            $order = Order::find($id);
            if (!empty($order)) {
                $user = User::find($order->user_id);      
                if (!empty($user)) {
                     // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
                    //$user->notify(new OrderProcessNotification($id, (int)$status));
                }    
            }
        }

    }

   

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'freight1' => 'nullable|numeric|min:0',
            'freight2' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'service' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->with('message', 'Error: Lỗi không thể cập nhật, dữ liệu cung cấp không phù hợp')
            ->with('status', 'danger');
        }

        $this->save($request, $id);

        
        return redirect()->back()
        ->with('message', 'Lưu thay đổi thành công!')
        ->with('status', 'success');
    }
    public function sendshop(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|numeric|min:1',
            'freight1' => 'nullable|numeric|min:0',
            'freight2' => 'nullable|numeric|min:0',
            'service' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->with('message', 'Error: Lỗi không thể cập nhật, dữ liệu cung cấp không phù hợp')
            ->with('status', 'danger');
        }


        $this->save($request, $id);

        $orderdetails_group = DB::table('orderdetails')
        ->select(DB::raw('shop_id'), DB::raw('sum(total) as total'))
        ->groupBy(DB::raw('shop_id') )
        ->get();

        $orderdetails = OrderDetail::where('order_id', $id)->get();

        $user_id = Auth::user()->id;
        
        DB::beginTransaction(); // LIVE STREAM
        try {
            foreach ($orderdetails as $os) {
                $ordershop = OrderShop::where('shop_id', $os->shop_id)->where('status', 1)->first();

                if (!empty($ordershop)) {
                    DB::table('ordershops')->where('id', $ordershop->id)->update(['totalamount' => $ordershop->totalamount + $os->total]);
                    DB::table('orderdetails')->where('id', $os->id)->update(['ordershop_id' => $ordershop->id]);
                } else {
                    $shop_id = DB::table('ordershops')->insertGetId([
                                'freight1'=>0,
                                'freight2'=>0,
                                'totalamount' => $os->total,
                                'status' => 1,
                                'landingcode'=>'',
                                'orderdate'=>Carbon::now(),
                                'shop_id' =>  $os->shop_id,
                                'usercreated' => $user_id ,
                                'userupdated' =>  $user_id ,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                    DB::table('orderdetails')->where('id', $os->id)->update(['ordershop_id' => $shop_id]);
                }
            }
            DB::table('orders')->where('id', $id)->update(['status' =>2]);
            
            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
               
            return redirect()->back()
            ->with('message', $e->getMessage()) //Đã xảy ra lỗi, không thể tạo đơn hàng
            ->with('status', 'danger')
            ->withInput();
        }

        $order = Order::find($id);
        if (!empty($order)) {
            $user = User::find($order->user_id);      
            if (!empty($user)) {
                 // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
                $user->notify(new OrderProcessNotification($id, 2));
            }    
        }

        return redirect()->back()
        ->with('message', 'Lưu thay đổi và tạo đơn hàng theo cửa hàng thành công!')
        ->with('status', 'success');
    }

    public function ajustunitprice(Request $request, $id, $orderdetail_id, $unitprice)
    {
        // $validator = Validator::make($request->all(), [
        //     'unitprice' => 'required|numeric|min:0'
        // ]);
        
        // if ($validator->fails()) {
        //     // return redirect()->back()
        //     // ->with('message', 'Bạn nhập giá tiền không phù hợp. Ví dụ đúng: 123456789.79')
        //     // ->with('status', 'danger');
        //     session()->flash('error_message', 'Bạn nhập giá tiền không phù hợp. Ví dụ đúng: 123456789.79');
        //     return response()->json(['success' => false]);
        // }
        if ($unitprice === 0 || $unitprice === '0') {
            session()->flash('error_message', 'Bạn chưa nhập giá sản phẩm !');
            return response()->json(['success' => false]);
        }

        if (!is_numeric($unitprice) || $unitprice < 0) {
            session()->flash('error_message', 'Giá sản phẩm không hợp lệ !');
            return response()->json(['success' => false]);
        }


        ///TODO
        //1. Update unitprice of item
        $orderDetail = OrderDetail::find($orderdetail_id);
        $old_unitprice = $orderDetail->unitprice;

        if (empty($orderDetail)) {
            // return redirect()->back()
            // ->with('message', 'Sản phẩm không tồn tại trong hệ thống!')
            // ->with('status', 'danger');
            session()->flash('error_message', 'Sản phẩm không tồn tại trong hệ thống!');
            return response()->json(['success' => false]);
        }
        if ($old_unitprice == $unitprice) {
            session()->flash('error_message', 'Bạn chưa thực hiện sự thay đổi nào!');
            return response()->json(['success' => false]);
        }

        $orderDetail->unitprice = $unitprice;
        $orderDetail->total = $orderDetail->quantity * $orderDetail->unitprice;
        $orderDetail->userupdated = Auth::user()->id;
        $orderDetail->save();

        //2. Update totalamount of order
        $this->updateTotalamountOrder($id);

        // Delete old history
        History::where('orderdetail_id', $orderdetail_id)->where('attribute', 'unit_price')->delete();

        // create history
        $history = new History;
        $history->orderdetail_id = $orderdetail_id;
        $history->product_name = $orderDetail->productname;
        $history->attribute = 'unit_price';
        $history->old_value = $old_unitprice;
        $history->new_value = $unitprice;
        $history->url = route('front.orders.show', ['id'=>$id]);
        $history->operation = 'edit';
        $history->created_by = Auth::user()->username;
        $history->description = ' đã thay đổi';
        $history->save();

        $order = Order::find($id);
        $user = User::find($order->user_id);      
        if (!empty($user)) {
            // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
            $user->notify(new OrderModifiedNotification($id, Auth::user()->username, 'edit', 'unit_price', $orderDetail->productname));
        }   

        //3. final
        // return redirect()->back()
        // ->with('message', 'Cập nhật giá tiền sản phẩm thành công!')
        // ->with('status', 'success');
        session()->flash('success_message', 'Cập nhật giá sản phẩm thành công!');
        return response()->json(['success' => true]);
    }

    public function adjust_size(Request $request, $id, $orderdetail_id)
    {

        //$orderdetail_id = $orderdetail_id; //request->input('orderdetail_id');
        //$size = $size; //request->input('size');
        $size = $request->size;
        if ($size == '' )
            $size = '-';
        $orderDetail = OrderDetail::find($orderdetail_id);
        $old_size = $orderDetail->size;

        if (empty($orderDetail)) {
            session()->flash('error_message', 'Sản phẩm không tồn tại trong hệ thống!');
            return response()->json(['success' => false]);
        }
        if ($old_size == $size) {
            session()->flash('error_message', 'Bạn chưa thực hiện sự thay đổi nào!');
            return response()->json(['success' => false]);
        }

        $orderDetail->size = $size;
        $orderDetail->userupdated = Auth::user()->id;
        $orderDetail->save();

        // Delete old history
        History::where('orderdetail_id', $orderdetail_id)->where('attribute', 'size')->delete();

        // create history
        $history = new History;
        $history->orderdetail_id = $orderdetail_id;
        $history->product_name = $orderDetail->productname;
        $history->attribute = 'size';
        $history->old_value = $old_size;
        $history->new_value = $size;
        $history->url = route('front.orders.show', ['id'=>$id]);
        $history->operation = 'edit';
        $history->created_by = Auth::user()->username;
        $history->description = ' đã thay đổi';
        $history->save();

        // $order = Order::find($id);
        // $user = User::find($order->user_id);      
        // if (!empty($user)) {
        //     // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
        //     $user->notify(new OrderModifiedNotification($id, Auth::user()->username, 'edit', 'size', $orderDetail->productname));
        // }   

        session()->flash('success_message', 'Cập nhật kích cỡ sản phẩm thành công!');
        return response()->json(['success' => true]);
    }

    public function adjust_color(Request $request, $id, $orderdetail_id, $color)
    {

        $orderDetail = OrderDetail::find($orderdetail_id);
        $old_color = $orderDetail->color;

        if (empty($orderDetail)) {
            session()->flash('error_message', 'Sản phẩm không tồn tại trong hệ thống!');
            return response()->json(['success' => false]);
        }
        if ($old_color == $color || ($old_color == '' && $color == 'null')) {
            session()->flash('error_message', 'Bạn chưa thực hiện sự thay đổi nào!');
            return response()->json(['success' => false]);
        }

        $orderDetail->color = ($color != 'null' ? $color : '');
        $orderDetail->userupdated = Auth::user()->id;
        $orderDetail->save();

        // Delete old history
        History::where('orderdetail_id', $orderdetail_id)->where('attribute', 'color')->delete();

        // create history
        $history = new History;
        $history->orderdetail_id = $orderdetail_id;
        $history->product_name = $orderDetail->productname;
        $history->attribute = 'color';
        $history->old_value = $old_color;
        $history->new_value = $color;
        $history->url = route('front.orders.show', ['id'=>$id]);
        $history->operation = 'edit';
        $history->created_by = Auth::user()->username;
        $history->description = ' đã thay đổi';
        $history->save();

        $order = Order::find($id);
        $user = User::find($order->user_id);      
        if (!empty($user)) {
            // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
            $user->notify(new OrderModifiedNotification($id, Auth::user()->username, 'edit', 'color', $orderDetail->productname));
        }   

        session()->flash('success_message', 'Cập nhật màu sắc sản phẩm thành công!');
        return response()->json(['success' => true]);
    }

    public function adjust_quantity(Request $request, $orderid, $id, $quantity)
    {        
        $orderDetail = OrderDetail::find($id);
        $old_quantity = $orderDetail->quantity;

        if (!ctype_digit(strval($quantity)) || $quantity <= 0) {
            session()->flash('error_message', 'Số lượng không phù hợp!');
            return response()->json(['success' => false]);
        }

        if ($old_quantity == $quantity) {
            session()->flash('error_message', 'Bạn chưa thực hiện sự thay đổi nào!');
            return response()->json(['success' => false]);
        }

        $orderDetail->quantity = $quantity;//$request->quantity;
        $orderDetail->total = $orderDetail->quantity * $orderDetail->unitprice;
        $orderDetail->userupdated = Auth::user()->id;
        $orderDetail->save();

        $this->updateTotalamountOrder($orderid);

        // Delete old history
        History::where('orderdetail_id', $id)->where('attribute', 'quantity')->delete();

        // create history
        $history = new History;
        $history->orderdetail_id = $id;
        $history->product_name = $orderDetail->productname;
        $history->attribute = 'quantity';
        $history->old_value = $old_quantity;
        $history->new_value = $quantity;
        $history->url = route('front.orders.show', ['id'=>$orderid]);
        $history->operation = 'edit';
        $history->created_by = Auth::user()->username;
        $history->description = ' đã thay đổi';
        $history->save();

        $order = Order::find($orderid);
        $user = User::find($order->user_id);      
        if (!empty($user)) {
            // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
            $user->notify(new OrderModifiedNotification($id, Auth::user()->username, 'edit', 'quantity', $order_detail->productname));
        }   

        // try {
        //     $this->updateTotalamountOrder($orderid);
        // } catch (\Exception $e) {
        //     session()->flash('success_message', 'value:'. $e->getMessage());
        //     return response()->json(['success' => false]);
        // }
       
        session()->flash('success_message', 'Đã cập nhật số lượng sản phẩm !');
        return response()->json(['success' => true]);
    }

    public function ajustShopName(Request $request, $id, $name)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required'
        // ]);
        
        // if ($validator->fails()) {
        //     session()->flash('error_message', 'Yêu cầu nhập tên cửa hàng!');
        //     return response()->json(['success' => false]);
        //     // return redirect()->back()
        //     // ->with('message', 'Yêu cầu nhập tên cửa hàng!')
        //     // ->with('status', 'danger');
        // }
        
        if (strlen($name)<=0) {
            session()->flash('error_message', 'Yêu cầu nhập tên cửa hàng!');
            return response()->json(['success' => false]);
        }
        
        $shop = Shop::find($id);
        $shop->name= $name; //$request->input('name');
        $shop->save();

        session()->flash('success_message', 'Cập nhật tên cửa hàng thành công!');
        return response()->json(['success' => true]);
        // return redirect()->back()
        // ->with('message', 'Cập nhật tên cửa hàng thành công!')
        // ->with('status', 'success');
    }

    public function setAvailable(Request $request, $id)
    {
        $freight1detail = Freight1Detail::find($id);

        if (!empty($freight1detail)) {
           // $orderdetail->is_available = (int)$request->is_available;
           // Lỗi trên server: không nhận được request này???

           //fix
            if ($freight1detail->is_available>=1) {
                $freight1detail->is_available = 0;
            } else {
                $freight1detail->is_available = 1;
            }

            $freight1detail->save();

            session()->flash('success_message', 'Đánh dấu số lượng thành công!');
            return response()->json(['success' => true]);
        } else {
            session()->flash('success_message', 'Không tìm thấy sản phẩm!');
            return response()->json(['success' => false]);
        }
    }
    public function updateTotalamountOrder($orderid)
    {
        $order = Order::where('id', $orderid)->first();
        $totalamount = OrderDetail::where('order_id', $order->id)->sum('total');
        $order->totalamount = $totalamount;
        $order->save();

    }

    public function ajustfreightsub(Request $request, $id, $freight1detail_id, $unitprice)
    {
        // $validator = Validator::make($request->all(), [
        //     'unitprice' => 'numeric|min:0'
        // ]);
        
        // if ($validator->fails()) {
        //     return redirect()->back()
        //     ->with('message', 'Bạn nhập giá tiền không phù hợp. Ví dụ đúng: 123456789.79')
        //     ->with('status', 'danger');
        // }

        // $unitprice = $request->input('unitprice');
        // $freight1detail_id = $request->input('freight1detail_id');

        if (!is_numeric($unitprice)) {
            session()->flash('error_message', 'Bạn nhập giá tiền không phù hợp. Ví dụ đúng: 123456789.79');
            return response()->json(['success' => false]);
        }

        // 1. update value sub
        $freight1detail = Freight1Detail::where('id', $freight1detail_id)->first();
        $freight1detail->freight1_sub = $unitprice;
        $freight1detail->save();

        // 2. update freight1
        $total_freight1 = Freight1Detail::where('order_id', $id)->sum('freight1_sub');
        $order = Order::where('id', $id)->first();
        $order->freight1 = $total_freight1;
        $order->save();

        // 3. update total
        $this->updateTotalamountOrder($id);

        // 4. final
        // return redirect()->back()
        // ->with('message', 'Cập nhật giá tiền thành công!')
        // ->with('status', 'success');
        session()->flash('success_message', 'Cập nhật giá tiền thành công!');
        return response()->json(['success' => true]);
    }

    public function adjust_landingcode(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'landingcode' => 'required'
        // ]);
        
        // if ($validator->fails()) {
        //     // return redirect()->back()
        //     // ->with('message', 'Vui lòng nhập mã vận đơn.')
        //     // ->with('status', 'danger');
        //     session()->flash('error_message', 'Vui lòng nhập mã vận đơn.');
        //     return response()->json(['success' => false]);
        // }

        $code = $request->code;
        
        if (strlen($code)<=0) {
            session()->flash('error_message', 'Vui lòng nhập mã vận đơn.');
            return response()->json(['success' => false]);
        }

        $freight1detail = Freight1Detail::find($id);
        if(!empty($freight1detail))
        {
            $freight1detail->landingcode =  $code; //$request->input('landingcode');
            $freight1detail->save();
            // return redirect()->back()
            // ->with('message', 'Cập nhật mã vận đơn thành công.')
            // ->with('status', 'success');

            session()->flash('success_message', 'Cập nhật mã vận đơn thành công.');
            return response()->json(['success' => true]);
        }

        session()->flash('error_message', 'Dữ liệu không tìm thấy');
        return response()->json(['success' => false]);
        // return redirect()->back()
        // ->with('message', 'Dữ liệu không tìm thấy')
        // ->with('status', 'danger');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order =  Order::find($id);
        
        $order->status = 7;
        if($order->is_deposited != 1){ // nếu đơn hàng chưa được đặt cọc thì có thể đổi thời gian đặt hàng
            $order->created_at = Carbon::now();  
        }
        if($order->usercreated == null){ // nếu là người đầu tiên thay đổi trạng thái đơn hàng
            $order->usercreated = Auth::user()->id;  // thì trở thành người duyệt đơn
        }  

        $order->save();

        return redirect()->route('admin.orders.index')
                        ->with('message', 'Bạn vừa hủy một đơn hàng!')
                        ->with('status', 'success');
    }

    public function delete($id)
    {
        // $orderdetails = OrderDetail::where('order_id', $id)->get();
        // foreach ($orderdetails as $orderdetail) {
        //     History::where('orderdetail_id', $orderdetail->id)->delete();
        // }
        $order = Order::find($id);
        if(empty($order)) {
            return redirect()->back()
                             ->with('message', 'Đơn hàng không tồn tại ! ')
                             ->with('status', 'danger');
        }

        // History::where('orderdetail_id', function($query) use ($id){ 
        //     $query->select('id')
        //           ->from('orderdetails')
        //           ->where('order_id', $id);
        //     })->delete();
        $orderdetails = OrderDetail::where('order_id', $id)->get();
        foreach ($orderdetails as $orderdetail) {
            History::where('orderdetail_id', $orderdetail->id)->delete();
        }

        Freight1Detail::where('order_id', $id)->delete();
        OrderDetail::where('order_id', $id)->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')
                         ->with('message', 'Bạn vừa xóa một đơn hàng!')
                         ->with('status', 'success');
    }

    public function itemdestroy(Request $request, $id)
    {
        try {
            //delete item
            $order_detail = OrderDetail::find($id);
            $order_detail->quantity = 0;
            $order_detail->is_deleted = 1;
            $order_detail->total = 0;
            $order_detail->save();
            //$order_detail->delete();

            //update total amount
            $order = Order::where('id', $request->order_id)->first();
            $totalamount = OrderDetail::where('order_id', $order->id)->sum('total');
            $order->totalamount =  $totalamount;
            if ($totalamount<=0) {
                $order->status = 7;
            }

            $order->save();

            // create history
            $history = new History;
            $history->orderdetail_id = $id;
            $history->product_name = $order_detail->productname;
            $history->url = route('front.orders.show', ['id'=>$order_detail->order_id]);
            $history->operation = 'delete';
            $history->created_by = Auth::user()->username;
            $history->description = ' đã xóa';
            $history->save();

            $order = Order::find($order_detail->order_id);
            $user = User::find($order->user_id);      
            if (!empty($user)) {
                // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
                $user->notify(new OrderModifiedNotification($order_detail->order_id, Auth::user()->username, 'delete', '', $order_detail->productname));
            }   

        } catch (\Exception $e) {
            return redirect()->back()
            ->with('message', 'Error: '. $e->getMessage())
            ->with('status', 'danger');
        }
       

        return redirect()->back()
        ->with('message', 'Bạn vừa xóa một sản phẩm!')
        ->with('status', 'success');
    }

    public function find(Request $request)
    {
        return $this->filter($request);
    }


    public function filter(Request $request)
    {
        $landingCode = $request->input('landingCode');
        $user_create = $request->input('user_create');
        $customer = $request->input('customer');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $status = $request->get('status');
        
        if (empty($status) || (int)$status===0) {
            $statusIn =[1,2,3,4,5,6,7];
        } else {
            $statusIn=[$status];
        }


        $query  =DB::table('orders as O')->orderby('O.id', 'DESC')
        ->join('users as U', 'U.Id', '=', 'O.user_id')
        ->select('O.id', 'U.first_name', 'U.last_name', 'O.freight1', 'O.freight2', 'O.service', 'O.deposit', 'O.totalamount', 'O.status', 'O.created_at', 'O.usercreated')
        ->whereIn('O.status', $statusIn);

        if (!empty($landingCode)) {
            $Freight1DetailIds = Freight1Detail::where('landingcode','LIKE', '%'.$landingCode. '%')->pluck('order_id')->all();            
            $query->whereIn('O.id', $Freight1DetailIds);
        }

        if (!empty($fromDate)) {
                $from = date('Y-m-d'.' 00:00:00', strtotime($fromDate));
                $query->where('O.created_at', '>=', $from);
        }

        if (!empty($toDate)) {
                $to = date('Y-m-d'.' 23:59:59', strtotime($toDate));
                $query->where('O.created_at', '<=', $to);
        }

      

        if (!empty($customer)) {
            $query->where(function ($subQuery) use ($customer) {
                $subQuery->whereRaw("CONCAT(U.last_name, ' ', U.first_name) LIKE ?", '%'.$customer.'%');
				$subQuery->orWhereRaw("CONCAT(U.first_name, ' ', U.last_name) LIKE ?", '%'.$customer.'%');
                $subQuery->orWhere('U.first_name', 'LIKE', '%'.$customer.'%');
                $subQuery->orWhere('U.last_name', 'LIKE', '%'.$customer.'%');						
            });
        }

        if($user_create == 1){
            $query->where('usercreated', Auth::user()->id);
        }

        $orders = $query->paginate(20);


        return view('admin.orders.index', compact('orders', 'customer', 'fromDate', 'toDate', 'status'))
        ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    public function export($id, $type = 'xlsx')
    {
        $order = Order::where('id', $id)->first();

        if (empty($order)) {
        }

        $query = DB::table('orders as o')
        ->join('orderdetails as od', 'od.order_id', '=', 'o.id')
        ->select('od.url', 'od.productname', 'od.size', 'od.color', 'od.unitprice', 'od.quantity', 'od.total', 'o.freight1', 'o.freight2', 'o.totalamount')
        ->where('o.id', $order->id);

       
        if ($order->status===1) {
            $status = "Chờ xử lý";
        } elseif ($order->status===2) {
            $status = "Đang xử lý";
        } elseif ($order->status===3) {
            $status = "Chờ đặt cọc";
        } elseif ($order->status===4) {
            $status = "Đã đặt cọc";
        } elseif ($order->status===5) {
            $status = "Khiếu nại";
        } elseif ($order->status===6) {
            $status = "Đã hoàn thành";
        } elseif ($order->status===7) {
            $status = "Hủy";
        } else {
            $status = "Không xác định";
        }

        $data = array(
            array('Khách Hàng:', $order->user->last_name.' '.$order->user->first_name),
            array('Địa Chỉ Nhận Hàng:', $order->shipaddress.', '.$order->shipdistrict.', '.$order->shipcity),
            array('Điện Thoại:', $order->shipphone),
            array('Ngày Đặt Hàng:', date('d-m-Y', strtotime($order->created_at))),
            array('Trạng Thái:', $status),
            array(),
            array('STT','Tên Sản Phẩm', 'Kích Cỡ', 'Màu Sắc','Đơn Giá', 'Số Lượng','Tổng Tiền', 'Url'),
        );

        $i = 0;
        foreach ($query->get() as $item) {
            $i++;
            $data[] = array($i, $item->productname, $item->size, $item->color,number_format($item->unitprice, 2, ',', '.'), $item->quantity,number_format($item->total, 2, ',', '.'),$item->url);
        }

        $data[] = array();
        $data[] = array('Tổng Giá Sản Phẩm:', number_format($order->totalamount, 2, ',', '.') . ' Tệ');
        $data[] = array('Tỷ Giá Ngoại Tệ:', '1/'.$order->rate);
        $data[] = array('Phí Vận Chuyển 1:', number_format($order->freight1, 2, ',', '.') . ' Tệ');
        $data[] = array('Phí Vận Chuyển 2:', number_format($order->freight2, 0, ',', '.') . ' VNĐ');
        $data[] = array('Khối Lượng:', $order->weight);
        $data[] = array('Phí Dịch Vụ:', number_format($order->getServicePrice(), 0, ',', '.') . ' VNĐ');
        $data[] = array('Thành Tiền:', number_format($order->getFinalPrice(), 0, ',', '.') . ' VNĐ');
        $data[] = array('Đặt Cọc:', number_format($order->deposit, 0, ',', '.') . ' VNĐ');
        $data[] = array('Số Tiền Cần Thanh Toán:', number_format($order->getDebtPrice(), 0, ',', '.') . ' VNĐ');

        $exportFileName ="DonHang-". Carbon::now()->subDay()->format('Ymdhis');

        return Excel::create($exportFileName, function ($excel) use ($data) {
            $excel->sheet('OrderDetail', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);

                $range = "A7:H" . (count($data)-5);
                $sheet->setBorder($range, 'thin');

                $sheet->cells('A1:A5', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setFontWeight('bold');
                });
            });
        })->download($type); //xls, csv
    }

    public function route(){
        return view('admin.orders.route'); 
    }
    public function setRoute(Request $request){
        $this->validate($request,['landingcode'=>'required'],['required' => 'Hãy nhập mã vận đơn !']);
        $landingcode = $request->landingcode;
        $freight1 = Freight1Detail::where('landingcode',$landingcode)->first();
        if(empty($freight1)) {
            return redirect()->back()
                             ->with('message', 'Mã vận đơn không tồn tại !')
                             ->with('status', 'danger');
        }
        else{
            if($freight1->status == 1){
                $freight1->status = 2;
                $freight1->save();
                return redirect()->back()
                                 ->with('message', 'Đã chuyển lộ trình đơn hàng của shop : Shop Trung Quốc -> Đang vận chuyển')
                                 ->with('status', 'success');
            }
            elseif($freight1->status == 2){
                $freight1->status = 3;
                $freight1->save();
                return redirect()->back()
                                 ->with('message', 'Đã chuyển lộ trình đơn hàng của shop : Đang vận chuyển -> Kho Đà Nẵng')
                                 ->with('status', 'success');
            }
            elseif($freight1->status == 3){
                return redirect()->back()
                                 ->with('message', 'Lộ trình đơn hàng của shop hiện tại là Kho Đà Nẵng, không thể chuyển !')
                                 ->with('status', 'danger');
            }
            else{
                return redirect()->back()
                                 ->with('message', 'Không thể xác định lộ trình đơn hàng !')
                                 ->with('status', 'danger');
            }
        }
    }

    public function productInfo(Request $request)
    {
        $url = $request->url;
        $product = new PagesController;
        $product_info = array();
        if(strpos($url,'.taobao.com')!= false){  // nếu sp thuộc trang taobao.com
            $product_info = $product->get_product_taobao($url);
        }
        elseif(strpos($url,'.tmall.com')!= false){
            $product_info = $product->get_product_tmall($url);
        }
        elseif(strpos($url,'.1688.com')!= false){
            $start_pos = strrpos($url, "/");
            $end_pos = strpos($url, ".html");
            $productId = substr($url, $start_pos+1, $end_pos-1-$start_pos);
            $product_info = $product->get_product_1688($productId);
        }

        if (empty($product_info)) {
            return response()->json(['success' => false, 'url' => $url]);
        }

        //session()->flash('success_message', 'Thêm sản phẩm mới thành công!');
        return response()->json(['success' => true,
                                 'url' => $url, 
                                 'name' => $product_info['name'],
                                 'image' => $product_info['image'],
                                 'size' => $product_info['sizes'],
                                 'color' => $product_info['colors'],
                                 'price' => $product_info['default_price'],
                                 'shop' => $product_info['shop_name'],
        ]);
    }

    public function addProduct(Request $request, $orderId)
    {

        $order = Order::find($orderId);

        if (empty($order)) {
            session()->flash('error_message', 'Đơn hàng không tồn tại trong hệ thống!');
            return response()->json(['success' => false]);
        }

        // shop
        $shopnotfound = "SHOP NOT FOUND";

        if (strlen($request->shop) > 0) {
            $shop = DB::table('shops')->where('name', $request->shop)->first();
        } 
        else {
            $shop = DB::table('shops')->where('name', $shopnotfound)->first();
        }
                        
        if (empty($shop)) {
            //TODO: insert new shop
            $shop_id= DB::table('shops')->insertGetId(['name' => (strlen($request->shop) > 0) ? $request->shop : $shopnotfound,
                                                       'created_at' => Carbon::now(),
                                                       'updated_at' => Carbon::now()
            ]);
        } 
        else {
            $shop_id = $shop->id;
        }

        $freight1 = DB::table('freight1details')->where('shop_id', $shop_id)->count();
        if ($freight1 == 0) { // nếu shop chưa tồn tại trong bảng freight1details thì insert
            DB::table('freight1details')->insert([
                                                  'order_id'=> $orderId,
                                                  'shop_id' => $shop_id,
                                                  'created_at' => Carbon::now(),
                                                  'updated_at' => Carbon::now()
            ]);
        }
        try{
            $orderDetail = new OrderDetail();
            $orderDetail->productname = $request->name;
            $orderDetail->size = (strlen($request->size) > 0) ? $request->size : '-';
            $orderDetail->color = strlen($request->color)> 0 ? $request->color : '-';
            $orderDetail->quantity = is_numeric($request->quantity) ? $request->quantity : 0;
            $orderDetail->unitprice = is_numeric($request->price) ? $request->price : 0;
            $orderDetail->total = $orderDetail->quantity * $orderDetail->unitprice;
            $orderDetail->image = $request->image;
            $orderDetail->url = $request->url;
            $orderDetail->order_id = $orderId;
            $orderDetail->shop_id = $shop_id;
            $orderDetail->note = $request->note;
            $orderDetail->usercreated = Auth::user()->id;
            $orderDetail->userupdated = Auth::user()->id;
            $orderDetail->created_at = Carbon::now();
            $orderDetail->updated_at = Carbon::now();

            $orderDetail->save();

            $this->updateTotalamountOrder($orderId);

            session()->flash('success_message', 'Thêm sản phẩm mới thành công! ');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            session()->flash('error_message', 'Đã xảy ra lỗi : '.$e->getMessage());
            return response()->json(['success' => false, 'error'=> $e->getMessage()]);
        }

        // // create history
        // $history = new History;
        // $history->orderdetail_id = $orderdetail_id;
        // $history->product_name = $orderDetail->productname;
        // $history->attribute = 'color';
        // $history->old_value = $old_color;
        // $history->new_value = $color;
        // $history->url = route('front.orders.show', ['id'=>$id]);
        // $history->operation = 'edit';
        // $history->created_by = Auth::user()->username;
        // $history->description = ' đã thay đổi';
        // $history->save();

        // $order = Order::find($id);
        // $user = User::find($order->user_id);      
        // if (!empty($user)) {
        //     // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
        //     $user->notify(new OrderModifiedNotification($id, Auth::user()->username, 'edit', 'color', $orderDetail->productname));
        // }   

        
    }

    public function test(){
        $shop = DB::table('shops')->where('name', 'dhdh')->count();
        //var_dump($status); die();
        echo "count = ".$shop; die();
    }
}
