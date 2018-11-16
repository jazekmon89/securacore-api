<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Istrator',
            'email' => 'admin@yahoo.com',
            'username' => 'admin',
            'password' => '$2y$11$9DpTaZlOKnlR8LodrYbNOOkb5DSvLBGnNkKk7lXZ1sIn/1zKX2xfW',
        ]);
    }
}
