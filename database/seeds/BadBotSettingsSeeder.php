<?php

use Illuminate\Database\Seeder;

class BadBotSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('badbot_settings')->insert([
            'badbot' => 0,
            'fakebot' => 1,
            'useragent_header' => 0,
            'website_id' => 1,
        ]);
    }
}
