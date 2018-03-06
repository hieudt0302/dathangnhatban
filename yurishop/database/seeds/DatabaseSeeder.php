<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //FOR SYSTEM - FIXED
        $this->call(PermissionTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        //FOR DEMO
        $this->call(BookAddressTableSeeder::class);
        $this->call(RatesTableSeeder::class);
        //$this->call(AppSettingsTableSeeder::class);
        
        //script re-new database 
        // DROP DATABASE IF EXISTS demo_wom;
        // CREATE DATABASE IF NOT EXISTS demo_wom CHARACTER SET utf8 COLLATE utf8_general_ci;
    }
}
