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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('fb_uid');
            $table->string('tw_uid');
            $table->string('ln_uid');
            $table->string('gg_uid');
            $table->string('gcm');
            $table->string('zip');
            $table->string('phone');
            $table->string('bio');
            $table->mediumText('image');
            $table->mediumText('cover');
            $table->string('website');
            $table->string('city');
            $table->string('country');
            $table->string('password')->nullable();
            $table->string('api_token');
            $table->string('confirmation_code');
            $table->integer('role');
            $table->boolean('confirmed')->default(config('access.users.confirm_email') ? false : true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
