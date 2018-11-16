<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'realtime' => 0,
            'mail' => 0,
            'ip_check' => 0,
            'countryban' => 1,
            'live_traffic' => 0,
            'jquery' => 1,
            'error_reporting' => 5,
            'display_errors' => 0,
            'user_id' => 1
        ]);
    }
}
