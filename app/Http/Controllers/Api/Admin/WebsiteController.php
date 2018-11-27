<?php

namespace App\Http\Controllers\Api\Admin;

use App\Jobs\ProcessClientInitialData;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\WebsiteStoreRequest;
use App\Http\Requests\Api\Admin\WebsiteUpdateRequest;
use App\User;
use App\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{

    public function index() {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $to_return = Website::paginate(10)->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function indexByUserId(User $user) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $website = Website::where('user_id', $user->id)->get();
            $to_return = $website->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function store(WebsiteStoreRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            if (!$request->has('user_id')) {
                return response()->json([
                    'error'=>'user_id needed'
                ], 400);
            }
            $website = new Website();
            $request = $request->all();
            $fillables = $website->getFillable();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $website->{$field} = $value;
                }
            }
            $website->save();
            ProcessClientInitialData::dispatch($website);
            $to_return = $website->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function storeWithUserId(User $user, WebsiteStoreRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $website = new Website();
            $request = $request->all();
            $fillables = $website->getFillable();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $website->{$field} = $value;
                }
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
        if (ApiHelper::isAdmin()) {
            $to_return = $website->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function update(Website $website, WebsiteUpdateRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $request = $request->all();
            $fillables = $website->getFillable();
            foreach($request as $field=>$value) {
                if ( ($value || $value === 0) && in_array($field, $fillables) ) {
                    $website->{$field} = $value;
                }
            }
            $website->save();
            $to_return = $website->getAttributes();
        }
        return response()->json($to_return, 200);
    }

    public function destroy(Website $website) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $website->delete();
            $to_return = ['success'=>true];
        }
        return response()->json($to_return, 200);
    }
    
}
