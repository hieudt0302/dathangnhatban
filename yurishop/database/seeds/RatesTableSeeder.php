<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rate;

class RatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Rate::count() == 0) {

            $user = User::where('username', 'admin')->firstOrFail();

            Rate::create([
                'rate'  => 3478,
                'user_id' => $user->id
            ]);
        }
    }
}
