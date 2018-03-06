<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Freight1Detail;
use DB;

class Order extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


     /**
     * Get the number of items in the cart.
     *
     * @return int|float
     */
    public static function countnew()
    {
        return DB::table('orders')->where('status', '1')->count(); // Đang chờ xử lý => New
    }

      /**
     * Get the number of items in the cart.
     *
     * @return int|float
     */
    public static function countwork()
    {
        return DB::table('orders')->where('status', '2')->count(); // Đang chờ xử lý => Work
    }

    public function convertTotalAmountToVietNamDong()
    {
        return $this->totalamount * $this->rate;
    }


    public function convertFreight1ToVietNamDong()
    {
        return $this->freight1 * $this->rate;
    }
    
    public function getFinalPrice()
    {
        return $this->convertTotalAmountToVietNamDong()  + $this->convertFreight1ToVietNamDong() + $this->getFreightVN() + $this->getServicePrice();
    }

    
    public function getFreightVN()
    {
        return $this->freight2 * $this->weight;
    }

    public function getDebtPrice()
    {
        return $this->getFinalPrice() - $this->deposit;
    }

    public function getServicePrice()
    {
        return $this->service * ($this->convertFreight1ToVietNamDong() + $this->convertTotalAmountToVietNamDong())/100;
    }

    public function statusRoute(){
        $status = Freight1Detail::where('order_id', $this->id)->pluck('status')->toArray();
        $route = 0;
        if(count(array_unique($status)) === 1){  // array_unique : loại bỏ phần tử trùng nhau trong mảng
            if(end($status) == 1){
                $route = 1;
            }
            elseif(end($status) == 2){
                $route = 2;
            }
            elseif(end($status) == 3){
                $route = 3;
            }
        }
        else{
            $route = 2;
        }
        return $route;
    }
}
