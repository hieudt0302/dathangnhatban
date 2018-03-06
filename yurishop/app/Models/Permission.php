<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}