<?php

namespace App\Http\Controllers\Api\Users;

use App\Website;
use App\User;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{

    public function index() {
        $to_return = [];
        $user = auth()->user();
        if (ApiHelper::canAccess() && auth()->user()) {
            $website = Website::where('user_id', $user->id);
            $to_return = $website->paginate(10)->toArray();
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
