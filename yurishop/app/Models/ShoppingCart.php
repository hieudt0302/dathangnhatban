<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
class ShoppingCart extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopping_cart';

    //$username = Auth::user()->username;

    public static function content(){
    	return DB::table('shopping_cart')->where('username', Auth::user()->username)->get();
    }
    public static function totalProduct(){
    	return DB::table('shopping_cart')->where('username', Auth::user()->username)->sum('quantity');
    }
    public static function totalMoney(){
    	return DB::table('shopping_cart')->where('username', Auth::user()->username)->sum('total_price');
    }
    public static function existProduct($productId){
    	$items = DB::table('shopping_cart')->where('username', Auth::user()->username)->where('product_id', $productId)->first();
    	if(!empty($items))
    		return true;
    	return false;
    }
}
