<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdetails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('productname');
            $table->string('size');
            $table->string('color');
            $table->integer('quantity');
            $table->decimal('unitprice', 12, 2);
            $table->decimal('total', 12, 2);
			$table->tinyInteger('is_deleted')->default(0);

            $table->text('image');
            $table->text('url');
            
            $table->boolean('is_available')->default(false);

            $table->integer('order_id')->unsigned();		
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('shop_id')->unsigned();		
            $table->foreign('shop_id')->references('id')->on('shops')
                ->onUpdate('cascade')->onDelete('cascade');
			
			$table->text('note')->nullable();
			$table->text('feedback')->nullable();

            $table->integer('ordershop_id')->nullable()->unsigned();
            $table->foreign('ordershop_id')->references('id')->on('ordershops')
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
        Schema::dropIfExists('orderdetails');
    }
}
