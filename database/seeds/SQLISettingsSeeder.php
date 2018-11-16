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
            'logging' => 1,
            'redirect' => 'http://securacore.securacoreinc.com/pages/blocked.php',
            'autoban' => 0,
            'mail' => 0,
            'client_id' => 1,
        ]);
    }
}
