<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveTrafficTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_traffic', function (Blueprint $table) {
            $table->increments('id');
            $table->char('ip', 15);
            $table->string('useragent', 255);
            $table->string('browser', 50);
            $table->string('os', 255);
            $table->string('os_code', 40);
            $table->string('device_type', 12);
            $table->string('country', 120);
            $table->char('country_code', 2)->default('XX');
            $table->string('request_uri', 255);
            $table->string('referer', 255);
            $table->tinyInteger('bot')->default('0');
            $table->date('date');
            $table->time('time');
            $table->tinyInteger('uniquev')->default('0');
            $table->unsignedInteger('website_id');
            $table->foreign('website_id')->references('id')->on('websites');
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
        Schema::dropIfExists('live_traffic');
    }
}
