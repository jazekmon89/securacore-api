<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Client;
use App\ContentSecurity;
use App\SecurityLabel;
use App\Http\Controllers\Controller;
use App\Security\Variations\ContentProtection;
use App\Http\Requests\SecurityUpdate;

class SecurityController extends Controller
{

    public function canAccess(Client $client) {
        if ( !$client->id ) {
            return false;
        }
        // for testing environments, let us give access
        if ( env('APP_ENV') != 'production' ){
            return true;
        }
        $referer = request()->server('HTTP_REFERER');
        $referer = !$referer ? request()->server('REMOTE_ADDR') : null;
        $referer_parsed = parse_url( $referer );
        $clients_flag = false;
        if ( !empty($referer) ) {
            $client_parsed = parse_url( $client->url );
            $client_host = !empty($client_parsed['host']) ? $client_parsed['host'] : (!empty($client_parsed['path']) ? $client_parsed['path'] : null);
            $referer_host = !empty($referer_parsed['host']) ? $referer_parsed['host'] : (!empty($referer_parsed['path']) ? $referer_parsed['path'] : null);
            if ( !$client_host || !$referer_host || ($client_host && $referer_host && $client_host != $referer_host) ) {
                return false;
            }
        }
        return true;
    }

    public function getSecurities(Client $client) {
        /*
         * We dynamically call the model for a given security to get the activator fields.
         * To achieve that, we created an associative array of security - model name
         *  [
         *      security name => [
         *          security class name,
         *          model class name
         *      ]
         *  ]
        */
        $securities = [
            'contentProtection'=>'ContentSecurity',
            'adBlockerProtection'=>'AdBlockSecurity',
            'dosProtection'=>'DoSSecurity',
            'proxyProtection'=>'ProxySecurity',
            'sqlProtection'=>'SQLSecurity',
            'spamProtection'=>'SpamSecurity',
            'botProtection'=>'BotSecurity'
        ];
        $to_return = [];
        if ( $this->canAccess($client) ) {
            foreach ($securities as $security => $model) {
                $model_name = 'App\\'.$model;
                $model = $model_name::where('client_id', $client->id)->first();
                $activator_value = $model ? $model->{$model_name::ACTIVATOR_FIELD} : 0;
                if ( isset($model->function) ) {
                    $to_return[$security] = ['is_enabled' => $activator_value, 'function' => []];
                    $functions = json_decode($model->function, 1);
                    $security_labels = SecurityLabel::whereIn('id', array_keys($functions))
                        ->get()
                        ->keyBy('id')
                        ->toArray();
                    foreach ($functions as $function_id => $function) {
                        $function_security_labels = !empty($security_labels[$function_id]) ? $security_labels[$function_id] : [];
                        $function['id'] = $function_id;
                        $function['message'] = $function_security_labels['message'];
                        $function_name = str_replace(' ', '_', $function_security_labels['name']);
                        $to_return[$security]['function'][$function_name] = $function;
                    }
                } else {
                    $to_return[$security] = ['is_enabled' => $activator_value];
                }
            }
        }
        return response()->json($to_return, 200);
    }

    public function getProtection(Client $client, $security_variation, $model) {
        $to_return = [];
        if ( $this->canAccess($client) ) {
            $to_return = $security_variation->get($client, $model);
            if ( !is_array($to_return) ) {
                $to_return = $to_return->getAttributes();
            }
        }
        return response()->json($to_return, 200);
    }

    public function setProtection(Client $client, $security_variation, $model, SecurityUpdate $fields) {
        $to_return = [];
        if ( $this->canAccess($client) ) {
            if ( !$fields ) {
                $to_return = $security_variation->updateSingleField($client, $model);
            } else {
                $to_return = $security_variation->update($client, $model, $fields);
            }
            if ( !is_array($to_return) ) {
                $to_return = $to_return->getAttributes();
            }
        }
        return response()->json($to_return, 200);
    }

    public function setContentProtectionByFunctionId(Client $client, $functionId) {
        $to_return = [];
        if ( $this->canAccess($client) ) {
            $contentProtection = new ContentProtection();
            $contentProtection = $contentProtection->updateJSONField($client, new ContentSecurity(), 'function', $functionId);
            $to_return = get_object_vars($contentProtection);
        }
        return response()->json($to_return, 200);
    }

    public function __call($name, $arguments) {
        if ( empty($arguments[0]) ) {
            return [];
        }
        $request = app('App\Http\Requests\SecurityUpdate');
        $client = app('App\Client');
        /*
         * We dynamically call the getProtection or setProtection for a given get or set function name.
         * To achieve that, we created an associative array of function name - class name and model name pair:
         *  [
         *      function name => [
         *          security class name,
         *          model class name
         *      ]
         *  ]
        */
        $functions = [
            'ContentProtection'=>['ContentProtection', 'ContentSecurity'],
            'AdBlockerProtection'=>['AdBlocker', 'AdBlockSecurity'],
            'DosProtection'=>['DoSProtection', 'DoSSecurity'],
            'ProxyProtection'=>['ProxyProtection', 'ProxySecurity'],
            'SqlProtection'=>['SQLInjectionProtection', 'SQLSecurity'],
            'SpamProtection'=>['SpamProtection', 'SpamSecurity'],
            'BotProtection'=>['BotProtection', 'BotSecurity']
        ];
        $security_variation = null;
        $model = null;
        $prefix = substr($name, 0, 3);
        foreach ($functions as $function => $class_model) {
            if ($name == $prefix.$function) {
                $security_name_space = 'App\\Security\\Variations\\';
                $model_name_space = 'App\\';
                $class_name = $security_name_space.$class_model[0];
                $security_variation = new $class_name;
                $model_name = $model_name_space.$class_model[1];
                $model = new $model_name();
                break;
            }
        }
        if ( $prefix == 'get' ) {
            return $this->getProtection($client, $security_variation, $model);
        } else if ( $prefix == 'set' ) {
            $original_request = request()->all();
            $request = new SecurityUpdate();
            $request->replace($original_request);
            return $this->setProtection($client, $security_variation, $model, $request);
        }
    }
}
