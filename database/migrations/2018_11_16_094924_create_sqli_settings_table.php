<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSqliSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sqli_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('sql_injection')->default('1');
            $table->tinyInteger('xss')->default('1');
            $table->tinyInteger('clickjacking')->default('1');
            $table->tinyInteger('mime_mismatch')->default('1');
            $table->tinyInteger('https')->default('0');
            $table->tinyInteger('data_filtering')->default('1');
            $table->tinyInteger('sanitation')->default('0');
            $table->tinyInteger('php_version')->default('0');
            $table->tinyInteger('logging')->default('1');
            $table->string('redirect', 255)->default('pages/blocked.php');
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
        Schema::dropIfExists('sqli_settings');
    }
}
