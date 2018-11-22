<?php

namespace App\Http\Controllers\Api\Users;

use App\Website;
use App\User;
use App\Helpers\ApiHelper;
use App\Http\Request\Api\UserRequest;
use Illuminate\Http\Request;


class WebsiteController extends Controller
{

    public function index() {
        $to_return = [];
        $user = auth()->user();
        if (ApiHelper::canAccess() && auth()->user()) {
            $website = Website::where('user_id', $user->id)->get();
            $to_return = $website->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function show(Website $website) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $to_return = $website->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function update(Website $website, WebsiteRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $request = $request->all();
            foreach($request as $field=>$value) {
                $website->{$field} = $value;
            }
            $website->save();
            $to_return = $website->getAttributes();
        }
        return response()->json($to_return, 200);
    }
    
}
