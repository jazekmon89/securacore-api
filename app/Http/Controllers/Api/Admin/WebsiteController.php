<?php

namespace App\Http\Controllers\Api\Admin;

use App\Jobs\ProcessClientInitialData;
use App\User;
use App\Website;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Request\Api\WebsiteRequest;
use Illuminate\Http\Request;


class WebsiteController extends Controller
{

    public function index() {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $website = Website::get();
            $to_return = $website->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function indexByUserId(Website $website, User $user) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $website = Website::where('user_id', $user->id)->get();
            $to_return = $website->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function store(WebsiteRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            if (!$request->has('user_id')) {
                return response()->json([
                    'error'=>'user_id needed'
                ], 400);
            }
            $website = new Website();
            $request = $request->all();
            foreach($request as $field=>$value) {
                $website->{$field} = $value;
            }
            $website->save();
            ProcessClientInitialData::dispatch($website);
            $to_return = $website->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function storeWithUserId(User $user, WebsiteRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $website = new Website();
            $request = $request->all();
            foreach($request as $field=>$value) {
                $website->{$field} = $value;
            }
            if (!$request->has('user_id')) {
                $website->user_id = $user->id;
            }
            $website->save();
            ProcessClientInitialData::dispatch($website);
            $to_return = $website->getAttributes();
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

    public function destroy(Website $website) {
        $website->delete();
        return response()->json(['success'=>true], 200);
    }
    
}
