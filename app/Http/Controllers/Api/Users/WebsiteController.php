<?php

namespace App\Http\Controllers\Api\Users;

use App\Website;
use App\User;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexFilterRequest;

class WebsiteController extends Controller
{

    public function index(IndexFilterRequest $request) {
        $to_return = [];
        $user = auth()->user();
        if (ApiHelper::canAccess() && auth()->user()) {
            $website = Website::where('user_id', $user->id);
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
            $to_return = $website->paginate($per_page, array('*'), 'page', $page)->toArray();
        }
        return response()->json($to_return, 200);
    }

    public function update(Website $website, WebsiteRequest $request) {
        $to_return = [];
        if (ApiHelper::canAccess()) {
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
    
}
