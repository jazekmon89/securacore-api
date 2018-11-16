<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banned', function (Blueprint $table) {
            $table->increments('id');
            $table->char('ip', 15);
            $table->date('date');
            $table->time('time');
            $table->string('reason', 255);
            $table->tinyInteger('redirect')->default('0');
            $table->string('url', 255);
            $table->tinyInteger('autoban')->default('0');
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
        Schema::dropIfExists('banned');
    }
}
