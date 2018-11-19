<?php

use Illuminate\Database\Seeder;

class DNSBLDatabasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dnsbl_databases')->insert([
            [
                'database' => 'sbl.spamhaus.org'
            ],[
                'database' => 'xbl.spamhaus.org'
            ]
        ]);
    }
}
