<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class History extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'history';

    public function show($admin_username, $operation, $attribute){
    	$history = $admin_username;

        if ($operation==='edit') {
            $history .= " đã thay đổi";
        } elseif ($operation==='delete') {
            $history .= " đã xóa sản phẩm này.";
        } else {
            $history .= "";
        }

        if ($attribute=='size') {
            $history .= ' kích cỡ';
        }
        elseif ($attribute=='color') {
            $history .= ' màu sắc';
        }
        elseif ($attribute=='quantity') {
            $history .= ' số lượng';
        }
        elseif ($attribute=='unit_price') {
            $history .= ' đơn giá';
        }
        else{
            $history .= '';
        }

        return $history;
    }
}
