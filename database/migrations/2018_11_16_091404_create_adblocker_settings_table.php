<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdblockerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adblocker_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('detection')->default('0');
            $table->string('redirect', 255)->default('pages/adblocker-detected.php');
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
        Schema::dropIfExists('adblocker_settings');
    }
}
