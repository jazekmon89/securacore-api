<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\ClientLogSubmitted;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class ClientLog extends TestCase
{
    /**
     * Customer Login API.
     *
     * @test
     */
    public function get_client_log()
    {

        $payload = [
            "public_key" => "381113",
            "ip" => "123.123.123.123",
            "date" => "2018-11-22",
            "time" => "11:39",
            "page" => "1",
            "query" => "testing",
            "type" => "bot",
            "browser_name" => "Google Chrome",
            "browser_code" => "chrome7",
            "os_name" => "Windows 10",
            "os_code" => "windows7",
            "country" => "Philippines",
            "country_code" => "PH",
            "region" => "region 7",
            "city" => "Cebu",
            "latitude" => "12.8797° N",
            "longitude" => "121.7740° E",
            "isp" => "123.123.123.123",
            "user_agent" => "Chrome 70.0.3538.77",
            "referer_url" => "http://localhost/debitis-a-iusto-sit-quia-debitis-velit-voluptatem",
            "website_id" => 5
        ];

        dd(env('JWT_TTL'));

        $response = $this->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])
        ->json('POST', '/api/log', $payload);
        // ->assertStatus(200);
            // ->assertJsonStructure([
            //     'success',
            //     'message',
            //     'token',
            //     'expires'
            // ]);

        dump($response->getContent());
    }
}
