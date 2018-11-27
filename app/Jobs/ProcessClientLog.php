<?php

namespace App\Jobs;

use Exception;
use App\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessClientLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $clientlog;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $clientlog)
    {
        $this->clientlog = $clientlog->all();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = new Log($this->clientlog);
        $log->save();
    }

     /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
        return $exception;
    }
}
