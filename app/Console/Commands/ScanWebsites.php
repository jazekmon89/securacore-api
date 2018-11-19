<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Client;

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
    protected $description = 'Scan client websites to check if online or offline';

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
        $clients_unscanned = Client::where('is_checked', 0)->limit(6);
        if ( $clients_unscanned->exists() ) {
            $clients_unscanned = $clients_unscanned->get();
            foreach( $clients_unscanned as $unscanned) {
                try {
                    $handle = fopen($unscanned->url, "r");
                    $unscanned->status = 1;
                    $unscanned->is_checked = 1;
                } catch (\Exception $ex) {
                    $unscanned->status = 0;
                    $unscanned->is_checked = 1;
                }
                $unscanned->save();
            }
        } else {
            /* 
             * All websites are scanned.
             * We need to reset the is_checked field.
             */
            Client::where('is_checked', 1)->update(['is_checked' => 0]);
        }
    }
}
