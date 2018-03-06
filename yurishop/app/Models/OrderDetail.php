<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Freight1Detail;
use DB;
class OrderDetail extends Model
{
    public $fillable = ['productname','size','color','quantity'];
    
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orderdetails';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }  

    public function getMaVanDon($shop_id)
    {
        $fr = Freight1Detail::where('order_id',$this->order->id)
        ->where('shop_id',$shop_id)->first();
        if(!isset($fr))
			return "";
        return $fr->landingcode;
    }

    public function shopRoute($shop_id)
    {
        $fr = Freight1Detail::where('order_id',$this->order->id)
        ->where('shop_id',$shop_id)->first();
        if(!isset($fr))
			return 0;
        return $fr->status;
    }
}
