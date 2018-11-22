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
        $adblock->detection = 1;
        $adblock->website_id = $website_id;
        $adblock->save();

        // Bot
        $bot = new BotSecurity();
        $bot->badbot = 1;
        $bot->fakebot = 1;
        $bot->useragent_header = 1;
        $bot->website_id = $website_id;
        $bot->save();

        // Content
        $content = new ContentSecurity();
        $content->enabled = 1;
        $content->function = '{"1":{"enabled":1,"alert":0},"2":{"enabled":1,"alert":0},"3":{"enabled":1,"alert":0},"4":{"enabled":1,"alert":0},"5":{"enabled":1,"alert":0},"6":{"enabled":1,"alert":0},"7":{"enabled":1,"alert":0},"8":{"enabled":1,"alert":0},"9":{"enabled":1,"alert":0},"10":{"enabled":1,"alert":0},"11":{"enabled":1,"alert":0},"12":{"enabled":1,"alert":0}}';
        $content->website_id = $website_id;
        $content->save();

        // DoS
        $dos = new DoSSecurity();
        $dos->security = 1;
        $dos->website_id = $website_id;
        $dos->save();

        // Proxy
        $proxy = new ProxySecurity();
        $proxy->proxy = 1;
        $proxy->proxy_headers = 1;
        $proxy->ports = 1;
        $proxy->website_id = $website_id;
        $proxy->save();

        // Spam
        $spam = new SpamSecurity();
        $spam->security = 1;
        $spam->website_id = $website_id;
        $spam->save();

        // SQL Injection
        $sql_injection = new SQLSecurity();
        $sql_injection->sql_injection = 1;
        $sql_injection->xss = 1;
        $sql_injection->clickjacking = 1;
        $sql_injection->mime_mismatch = 1;
        $sql_injection->https = 1;
        $sql_injection->data_filtering = 1;
        $sql_injection->sanitation = 1;
        $sql_injection->php_version = 1;
        $sql_injection->website_id = $website_id;
        $sql_injection->save();
    }
}
