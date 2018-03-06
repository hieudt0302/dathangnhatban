<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordershops', function (Blueprint $table) {
            $table->increments('id');
             $table->decimal('freight1', 12, 2);    				
            $table->decimal('freight2', 12, 2);
            $table->decimal('totalamount', 12, 2);
            $table->string('landingcode')->nullable();
            $table->tinyInteger('status');
            $table->dateTime('orderdate');
            $table->dateTime('shippeddate')->nullable();
            $table->string('note')->nullable(); 
            
            $table->integer('shop_id')->unsigned();		
            $table->foreign('shop_id')->references('id')->on('shops')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
            
            $table->integer('usercreated')->unsigned();
            $table->foreign('usercreated')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->integer('userupdated')->unsigned();
            $table->foreign('userupdated')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');                   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordershops');
    }
}
