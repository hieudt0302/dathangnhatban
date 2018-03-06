<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppSettingsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appsettings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('vip');
            $table->integer('service_percent')->default(0);
            $table->decimal('condition_min', 12, 2)->default(0);
            $table->decimal('condition_max', 12, 2)->default(0);
            $table->decimal('freight_per_kg', 12, 2)->default(0);
            $table->integer('deposit_percent')->default(0);

            $table->timestamps();
        });
    }
 
     /**
      * Reverse the migrations.
      *
      * @return void
      */
    public function down()
    {
        Schema::dropIfExists('appsettings');
    }
}
