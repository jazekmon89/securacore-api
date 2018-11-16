<?php

use Illuminate\Database\Seeder;

class ProxySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proxy_settings')->insert([
            'proxy' => 1,
            'proxy_headers' => 1,
            'ports' => 1,
            'logging' => 1,
            'autoban' => 1,
            'redirect' => 'http://securacore.securacoreinc.com/pages/blocked.php',
            'mail' => 1,
            'client_id' => 1
        ]);
    }
}
