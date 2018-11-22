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
            [
                'security' => 1,
                'website_id' => 1,
            ],
        ]);
    }
}
