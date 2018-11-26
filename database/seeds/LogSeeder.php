<?php

use Illuminate\Database\Seeder;
use App\Log;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $logs = factory(Log::class, 10)->make();
        foreach ($logs as $key => $log) {
            Log::create($log->toArray());
        }
    }
}
