<?php

namespace App\Http\Controllers\Api\Publics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Publics\SearchRequest;

class SearchController extends Controller
{

    public function search(SearchRequest $request) {
        $to_return = [];
        $http_code = 401;
        $field = 'public_key';
        $website = new Website();
        $public_key = $request->get($field) ?? null;
        if (ApiHelper::publicCheckAccess($public_key, $website, $field, $request)) {
        	$table_name = $request->get('table');
        	$text = $request->get('text') ?? null;
        	$start_date = $request->get('start_date') ?? null;
        	$end_date = $request->get('end_date') ?? null;
        	$date = $request->get('date') ?? null;
        	if (!$text && !$start_date && !$end_date && !$date) {
        		return response()->json([
        			'success' => 0,
        			'message' => "Empty request, there's no thing to search."
        		]);
        	}
            $per_page = $request->get('per_page') ?? 10;
            $page = $request->get('page') ?? 1;
        	$model = Helper::getModelByTable($table_name, 3);
        	if (!$model) {
        		return response()->json([
        			'success' => 0,
        			'message' => 'Table or Model not found'
        		]);
        	}
    		$searchables = $model::TEXT_SEARCHABLE;
        	if (count($searchables) > 0) {
        		if ($start_date) {
	        		$model = $model->whereBetween('created_at', [$start_date, $end_date]);
        		} else if ($date) {
        			$model = $model->whereDate('created_at', '=', $date);
        		} else {
	        		$model = $model->whereRaw("UPPER(" . array_shift($searchables) . ") LIKE '%" . $text . "%'");
		        	foreach ($searchables as $searchable_field) {
		        		$model->orWhereRaw("UPPER(" . $searchable_field . ") LIKE '%" . $text . "%'");
		        	}
	            }
	            $queries = $request->all();
	            $to_return = $model->paginate($per_page, array('*'), 'page', $page)
	            	->appends($queries)
	            	->toArray();
	        }
	        $http_code = 200;
        }
        return response()->json($to_return, $http_code);
    }

}
