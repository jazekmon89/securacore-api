<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBadbotSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('badbot_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('badbot')->default('1');
            $table->tinyInteger('fakebot')->default('1');
            $table->unsignedInteger('useragent_header');
            $table->tinyInteger('logging')->default('1');
            $table->tinyInteger('autoban')->default('0');
            $table->tinyInteger('mail')->default('0');
            $table->unsignedInteger('client_id');
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
        Schema::dropIfExists('badbot_settings');
    }
}
