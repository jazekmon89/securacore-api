<?php

use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'url' => 'site1.com',
            'notes' => '',
            'user_id' => 1,
            'public_key' => ''
        ],[
            'url' => 'google.com',
            'notes' => '',
            'user_id' => 1,
            'public_key' => ''
        ],[
            'url' => 'site2.com',
            'notes' => '',
            'user_id' => 0,
            'public_key' => ''
        ],[
            'url' => 'test4.com',
            'notes' => '',
            'user_id' => 0,
            'public_key' => ''
        ],[
            'url' => 'test5.com',
            'notes' => '',
            'user_id' => 0,
            'public_key' => ''
        ],[
            'url' => 'test6.com',
            'notes' => 'sample test
                            ',
            'user_id' => 0,
            'public_key' => ''
        ],[
            'url' => 'http://www.google.com',
            'notes' => '',
            'user_id' => 0,
            'public_key' => ''
        ]);
    }
}
