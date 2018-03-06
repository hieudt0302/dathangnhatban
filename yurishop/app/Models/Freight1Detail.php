<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freight1Detail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'freight1details';

     public function shop()
     {
         return $this->belongsTo(Shop::class);
     }
}
