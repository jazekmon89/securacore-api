<?php

use Illuminate\Database\Seeder;

class SQLISettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sqli_settings')->insert([
            'sql_injection' => 1,
            'xss' => 0,
            'clickjacking' => 0,
            'mime_mismatch' => 1,
            'https' => 1,
            'data_filtering' => 1,
            'sanitation' => 1,
            'php_version' => 1,
            'website_id' => 1,
        ]);
    }
}
