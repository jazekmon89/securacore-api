<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMassrequestsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('massrequests_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('security')->default('1');
            $table->tinyInteger('logging')->default('1');
            $table->tinyInteger('autoban')->default('0');
            $table->string('redirect', 255)->default('pages/mass-requests.php');
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
        Schema::dropIfExists('massrequests_settings');
    }
}
