<?php

use Illuminate\Database\Seeder;
use App\LiveTraffic;

class LiveTrafficSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $live_traffics = factory(LiveTraffic::class, 10)->make();
        foreach ($live_traffics as $key => $live_traffic) {
            LiveTraffic::create($live_traffic->toArray());
        }
    }
}
