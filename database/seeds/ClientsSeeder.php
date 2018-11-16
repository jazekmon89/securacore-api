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
            'notes' => ''
        ],[
            'url' => 'google.com',
            'notes' => ''
        ],[
            'url' => 'site2.com',
            'notes' => ''
        ],[
            'url' => 'test4.com',
            'notes' => ''
        ],[
            'url' => 'test5.com',
            'notes' => ''
        ],[
            'url' => 'test6.com',
            'notes' => 'sample test
                            '
        ],[
            'url' => 'http://www.google.com',
            'notes' => ''
        ]);
    }
}
