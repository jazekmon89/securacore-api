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
            'website_id' => 1
        ]);
    }
}
