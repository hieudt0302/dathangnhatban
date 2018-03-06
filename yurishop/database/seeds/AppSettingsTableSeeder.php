<?php

use Illuminate\Database\Seeder;
use App\Models\AppSetting;
class AppSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AppSetting::count() == 0) {
            AppSetting::create([
                'freight_to_vn'  => 32000,
            ]);
        }
    }
}
