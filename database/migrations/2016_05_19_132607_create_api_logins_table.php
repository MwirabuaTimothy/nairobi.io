<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('api_token');
            $table->string('gcm');
            $table->string('device');
            $table->string('platform');
            $table->string('version');
            $table->string('ip_address');
            $table->string('fb_code');
            $table->string('gg_code');
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
        Schema::drop('api_logins');
    }
}
