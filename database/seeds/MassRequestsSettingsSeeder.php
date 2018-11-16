<?php

use Illuminate\Database\Seeder;

class MassRequestsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('massrequests_settings')->insert([
            'security' => 0,
            'logging' => 1,
            'autoban' => 0,
            'redirect' => 'http://securacore.securacoreinc.com/pages/blocked.php',
            'mail' => 1,
            'client_id' => 1
        ]);
    }
}
