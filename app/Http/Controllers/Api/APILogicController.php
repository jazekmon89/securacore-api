<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use Validator;
use App\User;
use App\Website;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
// use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Notifiable as Notification;
use App\Http\Requests\Api\LogRequest;
use App\Jobs\ProcessClientLog;

class APILogicController extends Controller
{
    public function storeClientLog(LogRequest $clientlog)
    {
        $attacked_site = DB::table('websites')->where([
            ['id', '=', $clientlog->website_id],
            ['url', '=', $clientlog->referer_url],
            ['public_key', '=', $clientlog->public_key],
        ])->first();
        
        // dump($attacked_site);

        if ($attacked_site) {
            
            if ($attacked_site->is_activated === 1) {

                ProcessClientLog::dispatch($clientlog);

                return response()->json([
                    'success'   => true,
                    'message'   => 'Admin & Client has been notified of the attack!',
                    // 'data'   => $all
                ], 200);
            } else {

                ProcessClientLog::dispatch($clientlog);

                return response()->json([
                    'success'   => true,
                    'message'   => 'Admin & Client has been notified of the attack! Protection is not activated, please turn it on.',
                    // 'data'   => $all
                ], 200);
            } 

        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'URL is not yet registered as client domain.',
            ], 400);
        }
        
    }

    public function getUserWebsites(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            $invalid = new ValidationException($validator);
            return response()->json([
                'success'   => false,
                'message'   => $invalid->getMessage(),
                'errors'   => $invalid->errors()
            ], 500);
        }

        $checkUser = User::where('id', $request->user_id)->first();

        if (!$checkUser) {
            return response()->json([
                    'success'   => false,
                    'message'   => 'User ID does not exists.',
                ], 400);
        }

        $websites = Website::where('user_id', $request->user_id)->get();

        if (!count($websites)) {
            return response()->json([
                    'success'   => true,
                    'message'   => 'There are no registered websites yet for logged-in user.',
                ], 200);
        } else {
            return response()->json([
                    'success'   => true,
                    'message'   => 'Successfully retreived registered websites for logged-in user.',
                    'data'   => $websites,
                ], 200);
        }
        
    }
}
