<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use Validator;
use App\User;
use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Notifications\AttackNotification;
// use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Notifiable as Notification;

class APILogicController extends Controller
{
    public function notify(Request $request)
    {
        $all = $request->only([
            'client_id',
            'attack_type',
            'attack_message',
            'public_key',
            'url'
        ]);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'attack_type' => 'required',
            'attack_message' => 'required',
            'public_key' => 'required',
            'url' => 'required'
        ]);
        
        if ($validator->fails()) {
            $invalid = new ValidationException($validator);
            return response()->json([
                'success'   => false,
                'message'   => $invalid->getMessage(),
                'errors'   => $invalid->errors()
            ], 500);
        }
        $admin = User::where('role', 1)->first();
        
        $attacked_site = DB::table('clients')->where([
            ['id', '=', $all['client_id']],
            ['url', '=', $all['url']],
            ['public_key', '=', $all['public_key']],
        ])->first();
        // dump($attacked_site);
        
        if ($attacked_site) {
            $client = User::where('id', $attacked_site->user_id)->first();
            // dump($client);

            //email to admin
            $admin->sendAttackNotification($all);
            //email to client
            $client->sendAttackNotification($all);
            return response()->json([
                'success'   => true,
                'message'   => 'Admin & Client has been notified of the attack!',
                'data'   => $all
            ], 200);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'URL is not yet registered as client domain.',
            ], 400);
        }

    }
}
