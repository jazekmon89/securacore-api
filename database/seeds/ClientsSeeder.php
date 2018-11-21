<?php

use Illuminate\Database\Seeder;
use App\Client;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user_ids = DB::table('users')->pluck('id');
        $clients = factory(Client::class, 10)->make();
        
        foreach ($clients as $client) {
            $random_user_id = array_random(json_decode($user_ids, true));

            Client::create([
                'user_id' => $random_user_id,
                'url' => $client->url,
                'public_key' => $client->public_key,
                'is_activated' => $client->is_activated,
                'notes' => $client->notes,
                'status' => $client->status,
                'is_checked' => $client->is_checked
            ]);

        }
    }
}
