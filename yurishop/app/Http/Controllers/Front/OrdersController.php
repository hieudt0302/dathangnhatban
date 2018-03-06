<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\BookAddress;
use App\Models\Shop;
use App\Models\Rate;
use App\Models\ShoppingCart;
use DB;
use App\Models\Freight1Detail;
use Carbon\Carbon;
use Validator;
use App\Notifications\OrderResponsedNotification;
use App\Jobs\SendQueueEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;

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
        //empty cart
        if (ShoppingCart::totalProduct() <= 0) {
            return abort(404);
        }

        $bookaddress = DB::table('bookaddress')->where('user_id', Auth::user()->id)->get();
        $rates = Rate::orderBy('created_at', 'DESC')->first();
        if (empty($rates)) {
            $rate =0;
            // $final = floatval(Cart::total(2, '.', '')) *  $rate;
            // return view('front.orders.create', compact('bookaddress', 'rate', 'final'));
        } else {
            $rate = $rates->rate;
        }
        
        $final = ShoppingCart::totalMoney() * $rate;
        return view('front.orders.create', compact('bookaddress', 'rate', 'final'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Get data from blade
        $use_bookaddress = $request->input('use_bookaddress');
        $bookaddress_id = $request->input('bookaddress_id');

        //Address
        $shipaddress = $request->input('shipaddress');
        $shipdistrict = $request->input('shipdistrict');
        $shipdcity = $request->input('shipcity');
        $shipphone = $request->input('shipphone');
        
        if ($use_bookaddress === 'false' && strlen($shipaddress) <= 0) {
            return redirect()->back()
                ->with('message', 'Error: Vui lòng nhập địa chỉ.')
                ->with('status', 'danger')
                ->withInput();
        } elseif ($use_bookaddress === 'false' && strlen($shipdistrict) <=0 ) {
            return redirect()->back()
            ->with('message', 'Error: Vui lòng nhập Quận/Huyện.')
            ->with('status', 'danger')
            ->withInput();
        } elseif ($use_bookaddress === 'false' && strlen($shipdcity)<=0) {
            return redirect()->back()
            ->with('message', 'Error: Vui lòng nhập Tỉnh/Thành Phố')
            ->with('status', 'danger')
            ->withInput();
        } elseif ($use_bookaddress === 'false' && strlen($shipphone)<=0) {
            return redirect()->back()
            ->with('message', 'Error: Vui lòng nhập số điện thoại')
            ->with('status', 'danger')
            ->withInput();
        }

        //NOTE
        $note = $request->input('ordernote');
        

        //Choose address
        if ($use_bookaddress === 'true') {
            $bookaddress = BookAddress::where('id', $bookaddress_id)->first();
            if (!$bookaddress) {
                return redirect()->back()
                ->with('message', 'Vui lòng nhập địa chỉ nhận hàng')
                ->with('status', 'danger')
                ->withInput();
            }

            //Catch return bookaddress null
            $shipaddress = $bookaddress->address;
            $shipdistrict =$bookaddress->district;
            $shipdcity = $bookaddress->city;
            $shipphone =$bookaddress->phone;
        } else {
            $shipaddress = $request->input('shipaddress');
            $shipdistrict = $request->input('shipdistrict');
            $shipdcity = $request->input('shipcity');
            $shipphone = $request->input('shipphone');
        }

        // create an order
        $user_id = Auth::user()->id;

        $orderID = 0;

        DB::beginTransaction();
        try {
            //Get curent rate
            $rates = Rate::orderBy('created_at', 'DESC')->first();

            if (empty($rates)) {
                $rate = 0;
            } else {
                $rate = $rates->rate;
            }
            
            // $appsetting = DB::table('appsettings')->first();

            // Make order
            $order_id = DB::table('orders')->insertGetId([
                'rate'=>  $rate,
                'totalamount' => ShoppingCart::totalMoney(),
                'status' => 1,
                'shipaddress' => $shipaddress,
                'shipdistrict' => $shipdistrict,
                'shipcity' => $shipdcity,
                'shipphone' => $shipphone,
                'note' => $note,
                'user_id' => $user_id ,
                //'usercreated' => 0,
                //'userupdated' =>  0 ,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()

            ]);
           
           


            $orderID = $order_id;
            $shopArray = array();
           

            // Make orderdetails from session cart
            foreach (ShoppingCart::content() as $item) {

                $shopnotfound = "SHOP NOT FOUND";

                if (strlen($item->shop) > 0) {
                    $shop = DB::table('shops')->where('name', $item->shop)->first();
                } 
                else {
                    $shop = DB::table('shops')->where('name', $shopnotfound)->first();
                }
                
                
                if (empty($shop)) {
                    //TODO: insert new shop
                    if (strlen($item->shop) > 0) {
                        $shop_id= DB::table('shops')->insertGetId(
                            ['name' => $item->shop,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()]
                        );
                    } 
                    else {
                        $shop_id= DB::table('shops')->insertGetId(
                            ['name' => $shopnotfound, 
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()]
                        );
                    }
                } 
                else {
                    $shop_id = $shop->id;
                }

               
                if (!in_array($shop_id, $shopArray))
                {
                    array_push($shopArray,$shop_id);
                }
             
                

                DB::table('orderdetails')->insert([
                    'productname' => $item->name,
                    'size'=> strlen($item->size)> 0 ? $item->size:'-',
                    'color'=>strlen($item->color) > 0 ? $item->color:'-',
                    'quantity'=>$item->quantity,
                    'unitprice'=> is_numeric($item->unit_price) ? $item->unit_price : 0,
                    'total'=> is_numeric($item->total_price) ? $item->total_price : 0,
                    'image'=>$item->image,
                    'url'=>$item->url,
                    'order_id'=> $order_id,
                    'shop_id' => $shop_id,
                    'note' => $item->note,
                    'usercreated' => $user_id ,
                    'userupdated' =>  $user_id ,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            

            foreach($shopArray as $item)
            {
                DB::table('freight1details')->insert([
                    'order_id'=> $order_id,
                    'shop_id' => $item,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
            //var_dump($shopArray ); die();
            DB::commit();
            
            // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
           // Auth::user()->notify(new OrderProcessNotification($orderID, 1));

        } catch (\Exception $e) {
            // echo "<script>console.log( 'Return Exception: " . $e . "' );</script>";
            DB::rollBack();
           
            //Đã xảy ra lỗi, không thể tạo đơn hàng
            return redirect()->back()
            ->with('message', $e->getMessage())
            ->with('status', 'danger')
            ->withInput();
        }

        //remove cart
        ShoppingCart::where('username', Auth::user()->username)->delete();

        //return order list
        return redirect()->route('front.orders.index')
        ->with('message', 'Bạn đã đặt hàng thành công!')
        ->with('status', 'success');
    }


    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $freight1details = Freight1Detail::where('order_id',$id)->get();

        $orderdetails = OrderDetail::where('order_id', $order->id)->get();
        
         //$orderDetailByShops = OrderDetail::all()->where('order_id',$order->id)->groupBy('shop_id');
		$orderDetailByShops = OrderDetail::where('order_id',$order->id)->get()->groupBy('shop_id');
        $available  = $orderdetails->where('is_available', true)->count();
        // return view('front.orders.show', compact('order', 'orderdetails','freight1details'));
        return view('front.orders.show', compact('order', 'freight1details','orderDetailByShops','available'));
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

  
    public function destroy($id)
    {
        $order = Order::find($id);
        if(empty($order)){
            return redirect()->route('front.orders.index')
                             ->with('message', 'Đơn hàng không tồn tại trong hệ thống !')
                             ->with('status', 'danger');
        }

        if ($order->status===1) {
            $order->status = 7;
            $order->save();

            // send notification mail for admin
            //$email = 'taobaodanang@gmail.com';
            $email = 'thangld@jcs-corp.com';
            $name = Auth::user()->last_name . ' ' . Auth::user()->first_name;
            $content = $name . ' đã hủy đơn hàng của họ.';
            $data = array('email' => $email, 'content' => $content, 'orderId' => $id);
            Mail::send('front/orders/mail_template', $data, function($message) use ($data){
                $message->to($data['email'])->subject('Hủy đơn hàng');
            }); 
            return redirect()->back()
                             ->with('message', 'Bạn đã hủy đơn hàng thành công!')
                             ->with('status', 'success');
        } else {
            return redirect()->back()
            ->with('message', 'Đơn hàng này đang được xử lý, bạn không thể hủy ngay lúc này. Xin liên hệ bộ phận hỗ trợ!')
            ->with('status', 'danger');
        }
    }

    public function itemdestroy($id)
    {
    }


    public function note(Request $request, $id)
    {
            $order = Order::find($id);
            $order->note = $request->input('note');
            $order->save();
    
            return redirect()->back()
            ->with('message', 'Bạn đã thêm ghi chú thành công!')
            ->with('status', 'success');
    }

    public function feedback(Request $request, $id)
    {
            $order = Order::find($id);
            $order->feedback = $request->input('feedback');
            $order->save();
    
            return redirect()->back()
            ->with('message', 'Bạn đã gửi phản hồi thành công!')
            ->with('status', 'success');
    }

    public function itemfeedback(Request $request, $id)
    {
            $orderDetail = OrderDetail::find($id);
            $feedback = $request->input('feedback');
            if (empty($orderDetail)) {
                return redirect()->back()
                                 ->with('message', 'Sản phẩm này không tồn tại trong đơn hàng !')
                                 ->with('status', 'danger');
            }

            if (empty($feedback)) {
                return redirect()->back()
                                 ->with('message', 'Bạn chưa nhập nội dung khiếu nại !')
                                 ->with('status', 'danger');
            }

            $orderDetail->feedback = $feedback;
            $orderDetail->save();

            // send notification mail for admin
            //$email = 'taobaodanang@gmail.com';
            $email = 'thangld@jcs-corp.com';
            $name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $content = $name . ' đã khiếu nại về sản phẩm '. $orderDetail->productname . ' trong đơn hàng của họ.';
            $data = array('email' => $email, 'content' => $content, 'orderId' => $orderDetail->order_id);
            Mail::send('front/orders/mail_template', $data, function($message) use ($data){
                $message->to($data['email'])->subject('Khiếu nại sản phẩm');
            }); 
    
            return redirect()->back()
            ->with('message', 'Bạn đã gửi khiếu nại thành công, chúng tôi sẽ phản hồi lại cho bạn trong thời gian sớm nhất!')
            ->with('status', 'success');
    }

    public function find(Request $request)
    {
         return $this->filter($request);
    }

    public function setAvailable(Request $request, $id)
    {
        $orderdetail = OrderDetail::find($id);

        if (!empty($orderdetail)) {
           // $orderdetail->is_available = (int)$request->is_available;
           // Lỗi trên server: không nhận được request này??? Liên quan đến cache array

           //fix
            if ($orderdetail->is_available>=1) {
                $orderdetail->is_available = 0;
            } else {
                $orderdetail->is_available = 1;
            }

            $orderdetail->save();

            session()->flash('success_message', 'Đánh dấu số lượng thành công!');
            return response()->json(['success' => true]);
        } else {
            session()->flash('success_message', 'Không tìm thấy sản phẩm!');
            return response()->json(['success' => false]);
        }
    }
    public function filter(Request $request)
    {
        $status = $request->get('status');
     
        if ((int)$status===0) {
            $statusIn =[1,2,3,4,5,6,7];
        } else {
            $statusIn=[$status];
        }

        $fromDate = $request->input('fromDate');

        $toDate = $request->input('toDate');

        $landingCode = $request->input('landingCode');

        if (!empty($landingCode) && !empty($landingCode)) {
            $Freight1DetailIds = Freight1Detail::where('landingcode','LIKE', '%'.$landingCode. '%')->pluck('order_id')->all(); 
            $orders = Order::orderBy('id', 'DESC')
            ->where('user_id', Auth::user()->id)
            ->whereIn('id',$Freight1DetailIds)
            ->whereIn('status', $statusIn)
            ->paginate(10);
        } elseif (!empty($fromDate) && !empty($toDate)) {
            $from = date('Y-m-d'.' 00:00:00', strtotime($fromDate));
            $to = date('Y-m-d'.' 23:59:59', strtotime($toDate));
            $orders = Order::orderBy('id', 'DESC')
            ->where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', $statusIn)
            ->paginate(10);
        } elseif (!empty($fromDate)) {
            $from = date('Y-m-d'.' 00:00:00', strtotime($fromDate));
            $orders = Order::orderBy('id', 'DESC')
            ->where('user_id', Auth::user()->id)
            ->where('created_at', '>=', $from)
            ->whereIn('status', $statusIn)
            ->paginate(10);
        } elseif (!empty($toDate)) {
            $to = date('Y-m-d'.' 23:59:59', strtotime($toDate));

            $orders = Order::orderBy('id', 'DESC')
            ->where('user_id', Auth::user()->id)
            ->where('created_at', '<=', $to)
            ->whereIn('status', $statusIn )
            ->paginate(10);
        } else {
            $orders = Order::orderBy('id', 'DESC')
            ->where('user_id', Auth::user()->id)
            ->whereIn('status', $statusIn)
            ->paginate(10);
        }
        
        return view('front.orders.index', compact('landingCode','orders', 'fromDate', 'toDate', 'status'))
        ->with('i', ($request->input('page', 1) - 1) * 10);
    }

   
}
