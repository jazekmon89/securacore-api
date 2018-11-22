<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScanWebsites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websites:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan websites to check if online or offline';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websites_unscanned = DB::table('websites')->where('is_checked', 0)->limit(6);
        if ( $websites_unscanned->exists() ) {
            $websites = $websites_unscanned->get();
            $online_ids = [];
            $offline_ids = [];
            $test = [];
            foreach( $websites as $unscanned) {
                $result = Helper::domainIsAlive($unscanned->url);
                $test[] = $result;
                if (!$unscanned->status && $result) {
                    $online_ids[] = $unscanned->id;
                } else if ($unscanned->status && !$result) {
                    $offline_ids[] = $unscanned->id;
                }
            }
            DB::table('websites')
                ->whereIn('id', $online_ids)
                ->update([
                    'status' => 1
                ]);
            DB::table('websites')
                ->whereIn('id', $offline_ids)
                ->update([
                    'status' => 0
                ]);
            $websites_unscanned->update([
                'is_checked' => 1
            ]);
        }
        if ( !DB::table('websites')->where('is_checked', 0)->exists() ){
            /* 
             * All websites are scanned.
             * We need to reset the is_checked field.
             */
            DB::table('websites')->update(['is_checked' => 0]);
        }
    }
}
