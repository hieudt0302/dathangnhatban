<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->increments('id');
			$table->string('orderdetail_id');
			$table->string('product_name');
            $table->string('description')->nullable();
			$table->string('attribute')->nullable();
			$table->string('old_value')->nullable();
			$table->string('new_value')->nullable();
			$table->string('url');
			$table->string('operation');
			$table->string('created_by');
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
        Schema::dropIfExists('history');
    }
}
