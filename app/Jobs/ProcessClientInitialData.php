<?php

namespace App\Jobs;

use App\User;
use App\Website;
use App\AdBlockSecurity;
use App\BotSecurity;
use App\ContentSecurity;
use App\DoSSecurity;
use App\ProxySecurity;
use App\SpamSecurity;
use App\SQLSecurity;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessClientInitialData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $website;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $website_id = $this->website->id;
        // Ad Block
        $adblock = new AdBlockSecurity();
        $adblock->detection = 0;
        $adblock->website_id = $website_id;
        $adblock->save();

        // Bot
        $bot = new BotSecurity();
        $bot->badbot = 0;
        $bot->fakebot = 0;
        $bot->useragent_header = 0;
        $bot->website_id = $website_id;
        $bot->save();

        // Content
        $content = new ContentSecurity();
        $content->enabled = 0;
        $content->function = json_encode([]);
        $content->website_id = $website_id;
        $content->save();

        // DoS
        $dos = new DoSSecurity();
        $dos->security = 0;
        $dos->website_id = $website_id;
        $dos->save();

        // Proxy
        $proxy = new ProxySecurity();
        $proxy->proxy = 0;
        $proxy->proxy_headers = 0;
        $proxy->ports = 0;
        $proxy->website_id = $website_id;
        $proxy->save();

        // Spam
        $spam = new SpamSecurity();
        $spam->security = 0;
        $spam->website_id = $website_id;
        $spam->save();

        // SQL Injection
        $sql_injection = new SQLSecurity();
        $sql_injection->sql_injection = 0;
        $sql_injection->xss = 0;
        $sql_injection->clickjacking = 0;
        $sql_injection->mime_mismatch = 0;
        $sql_injection->https = 0;
        $sql_injection->data_filtering = 0;
        $sql_injection->sanitation = 0;
        $sql_injection->php_version = 0;
        $sql_injection->website_id = $website_id;
        $sql_injection->save();
    }
}
