<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('ip', 15);
            $table->date('date');
            $table->time('time');
            $table->string('page', 255);
            $table->text('query');
            $table->string('type', 50);
            $table->string('browser_name', 255)->default('Unknown');
            $table->string('browser_code', 50);
            $table->string('os_name', 255)->default('Unknown');
            $table->string('os_code', 40);
            $table->string('country', 120)->default('Unknown');
            $table->string('country_code', 2)->default('XX');
            $table->string('region', 120)->default('Unknown');
            $table->string('city', 120)->default('Unknown');
            $table->string('latitude', 30)->default('0');
            $table->string('longitude', 30)->default('0');
            $table->string('isp', 255)->default('Unknown');
            $table->text('user_agent');
            $table->string('referer_url', 255);
            $table->unsignedInteger('website_id');
            $table->foreign('website_id')->references('id')->on('websites');
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
        Schema::dropIfExists('logs');
    }
}
