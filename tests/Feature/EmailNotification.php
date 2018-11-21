<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailNotification extends TestCase
{
    /**
     * Customer Login API.
     *
     * @test
     */
    public function notify_email()
    {
        $payload = [
            "client_id" => 7,
            "attack_type" => "DDoS",
            "attack_message" => "what the hell is happening!",
            "public_key" => "19867",
            "url" => "crona.com"
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])
        ->json('POST', '/api/notify', $payload)
        ->assertStatus(200);

        // dd($response->getContent());
    }
}
