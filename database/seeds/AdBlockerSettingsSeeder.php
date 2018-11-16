<?php

use Illuminate\Database\Seeder;

class AdBlockerSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adblocker_settings')->insert([
            'detection' => 0,
            'redirect' => '/pages/adblocker-detected.php',
            'client_id' => 1
        ]);
    }
}
