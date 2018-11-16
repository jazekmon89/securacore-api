<?php

use Illuminate\Database\Seeder;

class ContentSecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_security')->insert([
            'client_id' => 1,
            'function' => '{"1":{"enabled":1,"alert":0},"2":{"enabled":1,"alert":0},"3":{"enabled":1,"alert":0},"4":{"enabled":1,"alert":0},"5":{"enabled":1,"alert":0},"6":{"enabled":1,"alert":0},"7":{"enabled":1,"alert":0},"8":{"enabled":1,"alert":0},"9":{"enabled":1,"alert":0},"10":{"enabled":1,"alert":0},"11":{"enabled":1,"alert":0},"12":{"enabled":1,"alert":0}}',
            'enabled' => 0,
        ]);
    }
}
