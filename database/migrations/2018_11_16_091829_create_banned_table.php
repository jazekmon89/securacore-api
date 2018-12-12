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
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('url', 255)->nullable();
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
        Schema::dropIfExists('banned');
    }
}
