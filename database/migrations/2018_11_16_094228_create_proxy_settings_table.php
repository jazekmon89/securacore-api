<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProxySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxy_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('proxy')->default('0');
            $table->tinyInteger('proxy_headers')->default('0');
            $table->tinyInteger('ports')->default('0');
            $table->tinyInteger('logging')->default('1');
            $table->tinyInteger('autoban')->default('0');
            $table->string('redirect', 255)->default('pages/proxy.php');
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
        Schema::dropIfExists('proxy_settings');
    }
}
