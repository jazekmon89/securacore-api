<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\WebsiteStoreRequest;
use App\Http\Requests\Api\Admin\WebsiteUpdateRequest;
use App\Http\Requests\Api\IndexFilterRequest;
use App\Jobs\ProcessClientInitialData;
use App\Notifications\AdminCreateUserWebsiteNotification;
use App\User;
use App\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class WebsiteController extends Controller
{

    public function index(IndexFilterRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = Website::paginate($per_page, array('*'), 'page', $page)
                ->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function indexByUserId(User $user, IndexFilterRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = Website::where('user_id', $user->id)
                ->paginate($per_page, array('*'), 'page', $page)
                ->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function store(WebsiteStoreRequest $request) {
        $to_return = [];
        if (ApiHelper::isAdmin()) {
            if (!$request->has('user_id')) {
                return response()->json([
                    'success' => 0,
                    'message' => 'user_id not found'
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
            if (!empty($request)) {
                $website->public_key = Helper::generatePublicKey();
                $website->is_activated = 0;
                $website->save();
                $user = User::where('id', $website->user_id)->first();
                ProcessClientInitialData::dispatch($website);
                Notification::send($user, new AdminCreateUserWebsiteNotification($website));
                $to_return = $website->toArray();
            }
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
