<?php

use Illuminate\Database\Seeder;

class SpamSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('spam_settings')->insert([
            'security' => 1,
            'logging' => 0,
            'redirect' => 'http://securacore.securacoreinc.com/pages/blocked.php',
            'autoban' => 0,
            'mail' => 0,
            'client_id' => 4,
        ],
    	[
    		'security' => 1,
            'logging' => 1,
            'redirect' => '/pages/spammer.php',
            'autoban' => 0,
            'mail' => 0,
            'client_id' => 1,
    	]);
    }
}
