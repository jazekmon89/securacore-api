<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $random = factory(User::class, 1)->make();
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Istrator',
            'email' => 'admin@yahoo.com',
            'username' => 'admin',
            'email_verified_at' => now(),
            // 'password' => '$2y$11$9DpTaZlOKnlR8LodrYbNOOkb5DSvLBGnNkKk7lXZ1sIn/1zKX2xfW',
            'password' => bcrypt('secretsecret'),
            'role' => 1, // admin
            'status' => 1, // active
            'admin_chat_token' => $random[0]->random_token,
            'created_at' => now()
        ]);

        $users = factory(User::class, 59)->make();

        foreach ($users as $user) {
            User::create([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'username' => null,
                'email_verified_at' => $user->email_verified_at,
                'password' => bcrypt($user->password),
                'role' => 2,
                'status' => $user->status
            ]);

        }
    }
}
