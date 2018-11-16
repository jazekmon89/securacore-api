<?php

use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->insert([
            'page' => 'Banned',
            'text' => 'You cannot continue to the website due to being banned.'
        ],
    	[
    		'page' => 'Blocked',
            'text' => 'You are prohibited to access this website due to being blocked.'
    	],
    	[
    		'page' => 'Mass_Requests',
            'text' => 'Warning, you performed too many connections.'
    	],
    	[
    		'page' => 'Proxy',
            'text' => 'Warning, Proxy detected; you are prohibited to access the website.(please disable Browser Data Compression)'
    	],
    	[
    		'page' => 'Spam',
            'text' => 'You cannot continue to the website due to Blacklisted Spammers.'
    	],
    	[
    		'page' => 'Banned_Country',
            'text' => 'Alert, Country location is banned and is prohibited to access the website'
    	],
    	[
    		'page' => 'Blocked_Browser',
            'text' => 'The browser you are using is not allowed.(Use another browser to continue)'
    	],
    	[
    		'page' => 'Blocked_OS',
            'text' => 'The Operating System you are using is not allowed.(Use another OS to continue)'
    	],
    	[
    		'page' => 'Blocked_ISP',
            'text' => 'The Internet Service Provider you are using is blacklisted; you are prohibited to access the webiste.'
    	],
    	[
    		'page' => 'Blocked_RFR',
            'text' => 'The referrer url you are using is blocked; you are prohibited to access the website.'
    	],
    	[
    		'page' => 'Tor',
            'text' => 'Alert, Tor Detected'
    	],
    	[
    		'page' => 'AdBlocker',
            'text' => 'Alert, AdBlocker detected.(please disable your AdBlocker to continue)'
    	],
    	[
    		'page' => 'Sqli',
            'text' => 'Alert, Sqli_injection detected '
    	],
    	[
    		'page' => 'Fake Bot',
            'text' => 'Alert, Fake Bot detected; you are prohibited to access the website.'
    	],
    	[
    		'page' => 'Bad Bot',
            'text' => 'Alert, Bad Bot detected; you are prohibited to access the website.'
    	]);
    }
}
