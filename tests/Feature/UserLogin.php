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
    public function user_login()
    {
        // $app = factory(User::class, 10)->make();
        // dd($app);
        // $this->assertTrue(true);
        $payload = ['email' => 'cornell.gibson@example.com','password' => 'secretsecret'];
        
        $response = $this->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])
        ->json('POST', '/api/login', $payload);
        // ->assertStatus(200);

        dd($response->getContent());
            // ->assertJsonStructure([
            //     'success',
            //     'message',
            //     'token',
            //     'data' => [
            //         "customer_id",
            //         "customer_code",
            //         "target_magic",
            //         "firstname",
            //         "lastname",
            //         "title",
            //         "status",
            //         "language",
            //         "email",
            //         "telno",
            //         "remarks",
            //         "is_pmp",
            //         "pmp_expiry",
            //         "default_service_advisor_id",
            //     ],
            // ]);
    }
}
