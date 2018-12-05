<?php

use Illuminate\Database\Seeder;
use App\Website;
use App\Jobs\ProcessClientInitialData;

class WebsitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user_ids = DB::table('users')->pluck('id');
        $websites = factory(Website::class, 60)->make();
        
        foreach ($websites as $key => $website) {
            //$random_user_id = array_random(json_decode($user_ids, true));

            $website = Website::create([
                'user_id' => ($key+1),
                //'user_id' => $random_user_id,
                'url' => $website->url,
                'public_key' => $website->public_key,
                'is_activated' => $website->is_activated,
                'notes' => $website->notes,
                'online' => $website->online,
                'is_scanned' => $website->is_scanned
            ]);
            ProcessClientInitialData::dispatch($website);
        }
    }
}
