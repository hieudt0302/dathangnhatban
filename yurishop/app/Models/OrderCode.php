<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OrderCode extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_code';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
