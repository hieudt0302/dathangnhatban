<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Administrator',
                    'description' => 'Tài khoản quản trị hệ thống!',
                ])->save();
        }
        $role = Role::firstOrNew(['name' => 'manager']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Quản Lý',
                    'description' => 'Tài khoản quản lý!',
                ])->save();
        }
        
        $role = Role::firstOrNew(['name' => 'normal']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Bình Thường',
                    'description' => 'Tài khoản thường!',
                ])->save();
        }
    }
}
