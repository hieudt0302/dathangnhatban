<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_cart', function (Blueprint $table) {
            $table->increments('id');
			$table->string('username');
			$table->string('product_id');
            $table->string('name',255);
			$table->string('color',255)->nullable();
			$table->string('color_img',1024)->nullable();
			$table->string('size',255)->nullable();
			$table->integer('quantity');
			$table->string('unit_price')->default('0');
			$table->string('total_price')->default('0');
			$table->string('shop',255)->nullable();
			$table->string('url',1024);
			$table->string('image',1024);
			$table->text('note')->nullable();
			$table->string('page');
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
        Schema::dropIfExists('shopping_cart');
    }
}
