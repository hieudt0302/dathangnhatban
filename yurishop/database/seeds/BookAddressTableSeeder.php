<?php

use Illuminate\Database\Seeder;
use App\Models\BookAddress;
use App\Models\User;
class BookAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                $user = User::where('username', 'admin')->firstOrFail();
             
                BookAddress::create([
                    'first_name'=>'Groot',
                    'last_name'=>'Em là',
                    'phone'=>'+84 123 456 789',
                    'fax'=>'0',
                    'address' => '02 Quang Trung, Thạch Thang',
                    'district' => 'Hải Châu',
                    'city' =>'Đà Nẵng',
                    'country'=>'Việt Nam',
                    'zipcode' =>'550000',
                    'company' => 'Japan Computer Software Co., Ltd',
                    'user_id' => $user->id
                ]);

                /* php artisan db:seed --class=BookAddressTableSeeder */
    }
}
