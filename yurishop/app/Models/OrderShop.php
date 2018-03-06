<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use DB;
class OrderShop extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ordershops';

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the number of items in the cart.
     *
     * @return int|float
     */
     public static function countnew()
     {
        return DB::table('ordershops')->where('status','1')->count(); // Đang chờ xử lý => New
     }

      /**
     * Get the number of items in the cart.
     *
     * @return int|float
     */
     public static function countwork()
     {
        return DB::table('ordershops')->where('status','2')->count(); // Đang chờ xử lý => Work
     }

     public function getFinalPrice() 
     {
       return  $this->freight1 + $this->freight2 + $this->totalamount;
     }
}
