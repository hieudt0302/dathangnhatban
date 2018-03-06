<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use DB;
use Validator;

class CartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $groupedCartItems = [];
            $carts = ShoppingCart::where('username', Auth::user()->username)->get();
            foreach ($carts as $item) {
                $groupedCartItems[$item->shop][] = $item->toArray();
            }
            //var_dump($groupedCartItems); die();
            return view('front.carts.index', compact('groupedCartItems'));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
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
        try {
            $page = $request->page; 
            $url = $request->url;
            $productName = isset($request->productName) ? $request->productName : 'N/A';
            $image = $request->image;
            $shop = isset($request->shop) ? $request->shop : '';
            $color = $request->color;
            $size = $request->size;
            $single = $request->singleProduct;  // tham số này chỉ dùng trong trường hợp sp chỉ có 1 màu sắc và kích cỡ duy nhất

            //print_r($single); die();

            if (is_array($size)) { // nếu sp có nhiều màu sắc và kích cỡ hoặc chỉ có nhiều kích cỡ
                foreach ($size as $s) {
                    if (!is_numeric($s['amount']) || (int)$s['amount']<=0) {
                        continue;
                    }

                    $GUID1 = hash('md5', $productName .$shop .  $s['size'] . (!empty($color) ? $color[0]['color'] : ''));

                    if(!ShoppingCart::existProduct($GUID1)){ // nếu sp chưa có trong giỏ hàng
                      $cart = new ShoppingCart;
                      $cart->username = Auth::user()->username;
                      $cart->product_id = $GUID1;
                      $cart->name = $productName;
                      $cart->quantity = $s['amount'];
                      $cart->unit_price = $s['price'];
                      if(is_numeric($s['price']))
                        $cart->total_price = $s['amount']*$s['price'];
                      $cart->size = $s['size'];
                      $cart->color = !empty($color) ? $color[0]['color'] : '';
                      $cart->color_img = !empty($color) ? $color[0]['colorImg'] : '';
                      $cart->shop = $shop;
                      $cart->url = $url;
                      $cart->image = $image;
                      $cart->note = $s['note'];
                      $cart->page = $page;
                      $cart->save();     
                    }
                    else{  // nếu sp đã có trong giỏ hàng
                      $item = ShoppingCart::where('username', Auth::user()->username)->where('product_id', $GUID1)->first();  
                      $item->quantity = $item->quantity + $s['amount'];
                      if(is_numeric($item->unit_price))
                        $item->total_price = $item->total_price + $s['amount']*$item->unit_price;
                      $item->save();
                    }               
                }
            } elseif (!empty($color)) {  // nếu sp chỉ có nhiều màu sắc
                foreach ($color as $c) {
                    if (!is_numeric($c['amount']) || (int)$c['amount']<=0) {
                        continue;
                    }
                    
                    $GUID2 = hash('md5', $productName .$shop . $c['color']);

                    if(!ShoppingCart::existProduct($GUID2)){
                      $cart = new ShoppingCart;
                      $cart->username = Auth::user()->username;
                      $cart->product_id = $GUID2;
                      $cart->name = $productName;
                      $cart->quantity = $c['amount'];
                      $cart->unit_price = $c['price'];
                      if(is_numeric($c['price']))
                        $cart->total_price = $c['amount']*$c['price'];
                      $cart->size = '';
                      $cart->color = $c['color'];
                      $cart->color_img = $c['colorImg'];
                      $cart->shop = $shop;
                      $cart->url = $url;
                      $cart->image = $image;
                      $cart->note = $c['note'];
                      $cart->page = $page;
                      $cart->save(); 
                    }
                    else{ // nếu sp đã có trong giỏ hàng
                      $item = ShoppingCart::where('username', Auth::user()->username)->where('product_id', $GUID2)->first();  
                      $item->quantity = $item->quantity + $c['amount'];
                      if(is_numeric($item->unit_price))
                        $item->total_price = $item->total_price + $c['amount']*$item->unit_price;
                      $item->save();
                    }  
                }
            } else {  // sp chỉ có 1 màu sắc và 1 kích cỡ duy nhất
                if (!is_numeric($single['amount']) || (int)$single['amount']<=0) {
                }
                    $GUID3 = hash('md5', $productName .$shop);

                    if(!ShoppingCart::existProduct($GUID3)){
                      $cart = new ShoppingCart;
                      $cart->username = Auth::user()->username;
                      $cart->product_id = $GUID3;
                      $cart->name = $productName;
                      $cart->quantity = $single['amount'];
                      $cart->unit_price = $single['price'];
                      if(is_numeric($single['price']))
                        $cart->total_price = $single['amount']*$single['price'];
                      $cart->size = '';
                      $cart->color = '';
                      $cart->color_img = '';
                      $cart->shop = $shop;
                      $cart->url = $url;
                      $cart->image = $image;
                      $cart->note = $single['note'];
                      $cart->page = $page;
                      $cart->save();  
                    }
                     else{ // nếu sp đã có trong giỏ hàng
                      $item = ShoppingCart::where('username', Auth::user()->username)->where('product_id', $GUID3)->first();  
                      $item->quantity = $item->quantity + $single['amount'];
                      if(is_numeric($item->unit_price))
                        $item->total_price = $item->total_price + $single['amount']*$item->unit_price;
                      $item->save();
                    } 
            }

            $totalProduct = ShoppingCart::totalProduct();
            session()->flash('success_message', 'Đã thêm sản phẩm vào giỏ hàng!');

            return response()->json(['success' => true, 'totalProduct'=> $totalProduct]);
        } catch (\Exception $e) {
            session()->flash('error_message', 'có lỗi xảy ra : '.$e->getMessage());
            return response()->json(['success' => false, 'error'=> $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $qty)
    {
       // var_dump($request->quantity);die();
        // Validation on max quantity
        // $validator = Validator::make($request->all(), [
        //     'quantity' => 'required|numeric'
        // ]);

        // if ($validator->fails()) {
        //     session()->flash('error_message', 'Số lượng không phù hợp!');
        //     return response()->json(['success' => false]);
        // }
        
        if (!is_numeric($qty)) {
            session()->flash('error_message', 'Số lượng không phù hợp!');
            return response()->json(['success' => false]);
        }

        $item = ShoppingCart::find($id);
        $item->quantity = $qty;
        if(is_numeric($item->unit_price))
          $item->total_price = $qty*$item->unit_price;
        $item->save();

        session()->flash('success_message', 'Số lượng đã cập nhật thành công!');

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShoppingCart::destroy($id);
        return redirect('cart')->withSuccessMessage('Sản phẩm đã được xóa!');
    }

    /**
     * Remove the resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function emptyCart()
    {
        ShoppingCart::where('username', Auth::user()->username)->delete();
        return redirect('cart')->withSuccessMessage('Giỏ hàng của bạn đã được xóa!');
    }

    function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid, 12, 4).$hyphen
                .substr($charid, 16, 4).$hyphen
                .substr($charid, 20, 12)
                .chr(125);// "}"
            return $uuid;
        }
    }
}
