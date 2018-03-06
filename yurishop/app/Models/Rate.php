<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rate extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rates';
    
    // public function user()
    // {
    //     return $this->hasOne('App\Models\User','id');
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
