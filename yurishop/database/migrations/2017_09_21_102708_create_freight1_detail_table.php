<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreight1DetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight1details', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('freight1_sub', 12, 2)->default(0);
			$table->string('landingcode')->nullable();
			$table->boolean('is_available')->default(false);

            $table->integer('order_id')->unsigned();		
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('shop_id')->unsigned();		
            $table->foreign('shop_id')->references('id')->on('shops')
                ->onUpdate('cascade')->onDelete('cascade');
				
			$table->tinyInteger('status')->unsigned()->default(1);
			
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
        Schema::dropIfExists('freight1details');

    }
}
