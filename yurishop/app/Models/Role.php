<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}