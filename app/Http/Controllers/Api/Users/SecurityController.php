<?php

namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Client;
use App\ContentSecurity;
use App\SecurityLabel;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Security\Variations\ContentProtection;
use App\Http\Requests\Api\SecurityUpdate;

class SecurityController extends Controller
{

    public function getSecurities(Client $client) {
        /*
         * We dynamically call the model for a given security to get the activator fields.
         * To achieve that, we created an associative array of security - model -> fields
         *  [
         *      security name => [
         *          security class name => [
         *              model class name => [ fields ]
         *          ]
         *      ]
         *  ]
        */
        $securities = [
            'contentProtection' => [
                'ContentSecurity' => []
            ],
            'adBlockerProtection' => [
                'AdBlockSecurity' => []
            ],
            'dosProtection' => [
                'DoSSecurity' => []
            ],
            'proxyProtection' => [
                'ProxySecurity' => [
                    'proxy_headers',
                    'ports'
                ]
            ],
            'sqlProtection' => [
                'SQLSecurity' => [
                    'xss',
                    'clickjacking',
                    'mime_mismatch',
                    'https',
                    'data_filtering',
                    'sanitation',
                    'php_version'
                ]
            ],
            'spamProtection' => [
                'SpamSecurity' => []
            ],
            'botProtection' => [
                'BotSecurity' => [
                    'fakebot',
                    'useragent_header'
                ]
            ]
        ];
        $to_return = [];
        if ( ApiHelper::canAccess() ) {
            foreach ($securities as $security => $model) {
                $model_name = key($securities[$security]);
                $model_name = 'App\\'.$model_name;
                $fields = current($model);
                $model = $model_name::where('client_id', $client->id)->first();
                $activator_value = $model ? $model->{$model::ACTIVATOR_FIELD} : 0;
                $to_return[$security] = ['is_enabled' => $activator_value];
                if ( isset($model->function) ) {
                    $to_return[$security]['function'] = [];
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
                }
                foreach($fields as $field) {
                    $to_return[$security][$field] = ['is_enabled' => $model->{$field}];
                }
            }
        }
        return response()->json($to_return, 200);
    }

    public function getProtection(Client $client, $security_variation, $model) {
        $to_return = [];
        if ( ApiHelper::canAccess() ) {
            $to_return = $security_variation->get($client, $model);
            if ( !is_array($to_return) ) {
                $to_return = $to_return->getAttributes();
            }
        }
        return response()->json($to_return, 200);
    }

    public function setProtection(Client $client, $security_variation, $model, SecurityUpdate $fields) {
        $to_return = [];
        if ( ApiHelper::canAccess() ) {
            if ( count($fields->all()) == 0 ) {
                $to_return = $security_variation->updateSingleField($client, $model);
            } else {
                $to_return = $security_variation->update($client, $model, $fields);
            }
            if ( $to_return && !is_array($to_return) ) {
                $to_return = $to_return->getAttributes();
            }
        }
        return response()->json($to_return, 200);
    }

    public function setJSONFieldById(Client $client, $security_variation, $model, $field_name, $functionId) {
        $to_return = [];
        if ( ApiHelper::canAccess() ) {
            $to_return = $security_variation->updateJSONField($client, new $model(), $field_name, $functionId);
            if ($to_return && !is_array($to_return)) {
                $to_return = $to_return->getAttributes();
            }
        }
        return response()->json($to_return, 200);
    }

    public function __call($name, $arguments) {
        if ( empty($arguments[0]) ) {
            return [];
        }
        $request = app('App\Http\Requests\Api\SecurityUpdate');
        $client = Client::where('id', $arguments[0])->first();
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
            'ContentProtection' => [
                'ContentProtection',
                'ContentSecurity'
            ],
            'AdBlockerProtection' => [
                'AdBlocker',
                'AdBlockSecurity'
            ],
            'DosProtection' => [
                'DoSProtection',
                'DoSSecurity'
            ],
            'ProxyProtection' => [
                'ProxyProtection',
                'ProxySecurity'
            ],
            'SqlProtection' => [
                'SQLInjectionProtection',
                'SQLSecurity'
            ],
            'SpamProtection' => [
                'SpamProtection',
                'SpamSecurity'
            ],
            'BotProtection' => [
                'BotProtection',
                'BotSecurity'
            ]
        ];
        $security_variation = null;
        $model = null;
        $prefix = substr($name, 0, 3);
        foreach ($functions as $function => $class_model) {
            if ($name == $prefix.$function || $name == $prefix.$function.'JSONFieldById') {
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
            $original_request = $request->all();
            unset($original_request['token']);
            $request = new SecurityUpdate();
            $request->replace($original_request);
            $json_function_name = 'JSONFieldById';
            $name = substr($name, -(strlen($json_function_name)));
            if ($name == $json_function_name) {
                $field_name = $arguments[1] ?? null;
                $sub_field_id = $arguments[2] ?? null;
                return $this->setJSONFieldById($client, $security_variation, $model, $field_name, $sub_field_id);
            } else {
                return $this->setProtection($client, $security_variation, $model, $request);
            }
        }
    }
}
