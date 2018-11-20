<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\DB;

class UserLogin extends TestCase
{
    /**
     * Customer Login API.
     *
     * @test
     */
    public function user_login_success()
    {
        // $app = factory(User::class, 10)->make();
        // dd($app);
        // $this->assertTrue(true);
        $payload = ['email' => 'josue.smitham@example.net','password' => 'secretsecret'];
        
        $response = $this->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])
        ->json('POST', '/api/login', $payload)
        ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'expires'
            ]);
    }

    /**
     * Failed Customer Login API.
     *
     * @test
     */
    public function user_login_failed()
    {
        $payload = ['email' => 'cornell.gibson@example.com','password' => 'secretsecret'];
        
        $response = $this->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])
        ->json('POST', '/api/login', $payload)
        ->assertStatus(401);
    }
}
