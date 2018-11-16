<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('realtime')->default('1');
            $table->tinyInteger('mail')->default('1');
            $table->tinyInteger('ip_check')->default('0');
            $table->tinyInteger('countryban')->default('1');
            $table->tinyInteger('live_traffic')->default('0');
            $table->tinyInteger('jquery')->default('0');
            $table->unsignedInteger('error_reporting')->default('5');
            $table->unsignedInteger('display_errors')->default('0');
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('settings');
    }
}
