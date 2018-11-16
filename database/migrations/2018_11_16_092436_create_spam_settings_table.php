<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpamSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spam_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('security')->default('0');
            $table->tinyInteger('logging')->default('1');
            $table->string('redirect', 255)->default('pages/spammer.php');
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
        Schema::dropIfExists('spam_settings');
    }
}
