<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username'); // modify name to username
            $table->string('first_name'); // add new
            $table->string('last_name'); // add new
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->rememberToken();   
            $table->boolean('activated')->default(false); //add new
			$table->boolean('deleted')->default(false);
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
        Schema::dropIfExists('users');
    }
}
